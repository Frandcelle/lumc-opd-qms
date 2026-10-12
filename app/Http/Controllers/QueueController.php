<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Events\TicketUpdated;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    // 1. Kiosk: Patient selects 'New' or 'Old' and gets a ticket number
    public function issueTicket(Request $request)
    {
        $request->validate([
            'patient_type' => 'required|in:new,old'
        ]);

        // Reset tracking counts daily (e.g., OPD-0001, OPD-0002)
        $todayCount = Ticket::whereDate('created_at', today())->count() + 1;
        $ticketNumber = 'OPD-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'patient_type' => $request->patient_type,
            'current_step' => 'triage',
            'status' => 'waiting'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket issued successfully!',
            'data' => $ticket
        ]);
    }

    // 2. Vital Signs Booth: Nurse clicks "Call Next Patient"
    public function nextToVitals(Request $request, $counterNumber)
    {
        // Get the oldest waiting patient still at the triage stage
        $ticket = Ticket::where('current_step', 'triage')
            ->where('status', 'waiting')
            ->orderBy('id', 'asc')
            ->first();

        if ($ticket) {
            $ticket->update([
                'current_step' => 'vitals',
                'status' => 'serving',
                'counter_number' => $counterNumber
            ]);

            // Broadcast real-time update to Lobby TV monitor
            event(new TicketUpdated($ticket));

            return response()->json(['status' => 'success', 'ticket' => $ticket]);
        }

        return response()->json(['status' => 'empty', 'message' => 'No patients waiting in Triage.']);
    }

    // 3. Vital Signs Booth: Nurse saves vitals data and sends patient to Registration Desk
    public function completeVitals(Request $request, $id)
    {
        $request->validate([
            'systolic_bp' => 'required|string',
            'diastolic_bp' => 'required|string',
            'temperature' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'systolic_bp' => $request->systolic_bp,
            'diastolic_bp' => $request->diastolic_bp,
            'temperature' => $request->temperature,
            'current_step' => 'registration', // Advance workflow to next department
            'status' => 'waiting',            // Reset status to wait for front desk
            'counter_number' => null          // Clear station until called by registrar
        ]);

        event(new TicketUpdated($ticket));

        return response()->json(['status' => 'success', 'message' => 'Vitals saved. Patient routed to Front Desk.']);
    }

    // 4. Front Desk: Registrar clicks "Call Next Patient" for Encoding
    public function nextToRegistration(Request $request, $counterNumber)
    {
        // Get the oldest waiting patient who completed their vitals
        $ticket = Ticket::where('current_step', 'registration')
            ->where('status', 'waiting')
            ->orderBy('id', 'asc')
            ->first();

        if ($ticket) {
            $ticket->update([
                'status' => 'serving',
                'counter_number' => $counterNumber
            ]);

            event(new TicketUpdated($ticket));

            return response()->json(['status' => 'success', 'ticket' => $ticket]);
        }

        return response()->json(['status' => 'empty', 'message' => 'No patients waiting in Registration queue.']);
    }

    // 5. Front Desk: Complete the entire cycle
    public function completeRegistration($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'current_step' => 'completed',
            'status' => 'completed'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Patient workflow finished.']);
    }
}
