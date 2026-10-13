<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Issue a queue number to an arriving OPD patient.
     */
    public function issueTicket(Request $request)
    {
        $validated = $request->validate([
            'patient_type' => 'required|in:new,old',
        ]);

        $todayCount = Ticket::whereDate('created_at', today())->count() + 1;

        $ticketNumber = 'OPD-' . now()->format('ymd') . '-'
            . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'patient_type' => $validated['patient_type'],
            'current_step' => 'triage',
            'status' => 'waiting',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Queue number issued successfully.',
            'data' => $ticket,
        ]);
    }

    /**
     * Call the next waiting patient to Vital Signs.
     */
    public function nextToVitals(Request $request, $counterNumber)
    {
        $ticket = Ticket::where('current_step', 'triage')
            ->where('status', 'waiting')
            ->orderBy('id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'empty',
                'message' => 'No patients waiting for Vital Signs.',
            ]);
        }

        $ticket->update([
            'current_step' => 'vitals',
            'status' => 'serving',
            'counter_number' => $counterNumber,
        ]);

        return response()->json([
            'status' => 'success',
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Save Vital Signs and send the ticket to OPD Registration.
     */
    public function completeVitals(Request $request, $id)
    {
        $validated = $request->validate([
            'systolic_bp' => 'required|string|max:20',
            'diastolic_bp' => 'required|string|max:20',
            'temperature' => 'required|string|max:20',
        ]);

        $ticket = Ticket::findOrFail($id);

        if (
            $ticket->current_step !== 'vitals' ||
            $ticket->status !== 'serving'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket is not currently being served at Vital Signs.',
            ], 422);
        }

        $ticket->update([
            'systolic_bp' => $validated['systolic_bp'],
            'diastolic_bp' => $validated['diastolic_bp'],
            'temperature' => $validated['temperature'],
            'current_step' => 'registration',
            'status' => 'waiting',
            'counter_number' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Vital Signs saved. Ticket moved to Registration.',
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Call the next waiting patient to OPD Registration.
     */
    public function nextToRegistration(Request $request, $counterNumber)
    {
        $ticket = Ticket::where('current_step', 'registration')
            ->where('status', 'waiting')
            ->orderBy('id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'empty',
                'message' => 'No patients waiting for Registration.',
            ]);
        }

        $ticket->update([
            'status' => 'serving',
            'counter_number' => $counterNumber,
        ]);

        return response()->json([
            'status' => 'success',
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Finish OPD Registration.
     */
    public function completeRegistration($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (
            $ticket->current_step !== 'registration' ||
            $ticket->status !== 'serving'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket is not currently being served at Registration.',
            ], 422);
        }

        $ticket->update([
            'current_step' => 'completed',
            'status' => 'completed',
            'counter_number' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration completed.',
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Return current ticket numbers and waiting counts for the TV display.
     * Patient names and clinical information are never returned here.
     */
    public function displayData()
    {
        $vitals = Ticket::where('current_step', 'vitals')
            ->where('status', 'serving')
            ->orderByDesc('updated_at')
            ->get(['ticket_number', 'counter_number']);

        $registration = Ticket::where('current_step', 'registration')
            ->where('status', 'serving')
            ->orderByDesc('updated_at')
            ->get(['ticket_number', 'counter_number']);

        return response()->json([
            'vitals' => $vitals,
            'registration' => $registration,
            'waiting_vitals' => Ticket::where('current_step', 'triage')
                ->where('status', 'waiting')
                ->count(),
            'waiting_registration' => Ticket::where('current_step', 'registration')
                ->where('status', 'waiting')
                ->count(),
        ]);
    }

    /**
     * Mark the currently called Vital Signs ticket as skipped.
     */
    public function skipVitals($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (
            $ticket->current_step !== 'vitals' ||
            $ticket->status !== 'serving'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket is not being served at Vital Signs.',
            ], 422);
        }

        $ticket->update([
            'status' => 'skipped',
            'counter_number' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket marked as skipped.',
        ]);
    }

    /**
     * Mark the currently called Registration ticket as skipped.
     */
    public function skipRegistration($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (
            $ticket->current_step !== 'registration' ||
            $ticket->status !== 'serving'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket is not being served at Registration.',
            ], 422);
        }

        $ticket->update([
            'status' => 'skipped',
            'counter_number' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket marked as skipped.',
        ]);
    }
}


// namespace App\Http\Controllers;

// use App\Models\Ticket;
// use Illuminate\Http\Request;

// class QueueController extends Controller
// {
//     public function issueTicket(Request $request)
//     {
//         $request->validate([
//             'patient_type' => 'required|in:new,old',
//         ]);

//         // Keep ticket numbers unique across different days.
//         $todayCount = Ticket::whereDate('created_at', today())->count() + 1;
//         $ticketNumber = 'OPD-' . now()->format('ymd') . '-'
//             . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

//         $ticket = Ticket::create([
//             'ticket_number' => $ticketNumber,
//             'patient_type' => $request->patient_type,
//             'current_step' => 'triage',
//             'status' => 'waiting',
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Ticket issued successfully.',
//             'data' => $ticket,
//         ]);
//     }

//     public function nextToVitals(Request $request, $counterNumber)
//     {
//         $ticket = Ticket::where('current_step', 'triage')
//             ->where('status', 'waiting')
//             ->orderBy('id')
//             ->first();

//         if (!$ticket) {
//             return response()->json([
//                 'status' => 'empty',
//                 'message' => 'No patients waiting in Triage.',
//             ]);
//         }

//         $ticket->update([
//             'current_step' => 'vitals',
//             'status' => 'serving',
//             'counter_number' => $counterNumber,
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'ticket' => $ticket->fresh(),
//         ]);
//     }

//     public function completeVitals(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'systolic_bp' => 'required|string|max:20',
//             'diastolic_bp' => 'required|string|max:20',
//             'temperature' => 'required|string|max:20',
//         ]);

//         $ticket = Ticket::findOrFail($id);

//         if ($ticket->current_step !== 'vitals' ||
//             $ticket->status !== 'serving') {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'This ticket is not currently being served at Vital Signs.',
//             ], 422);
//         }

//         $ticket->update([
//             'systolic_bp' => $validated['systolic_bp'],
//             'diastolic_bp' => $validated['diastolic_bp'],
//             'temperature' => $validated['temperature'],
//             'current_step' => 'registration',
//             'status' => 'waiting',
//             'counter_number' => null,
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Vital signs saved. Ticket moved to Registration.',
//             'ticket' => $ticket->fresh(),
//         ]);
//     }

//     public function nextToRegistration(Request $request, $counterNumber)
//     {
//         $ticket = Ticket::where('current_step', 'registration')
//             ->where('status', 'waiting')
//             ->orderBy('id')
//             ->first();

//         if (!$ticket) {
//             return response()->json([
//                 'status' => 'empty',
//                 'message' => 'No patients waiting in Registration.',
//             ]);
//         }

//         $ticket->update([
//             'status' => 'serving',
//             'counter_number' => $counterNumber,
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'ticket' => $ticket->fresh(),
//         ]);
//     }

//     public function completeRegistration($id)
//     {
//         $ticket = Ticket::findOrFail($id);

//         if ($ticket->current_step !== 'registration' ||
//             $ticket->status !== 'serving') {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'This ticket is not currently being served at Registration.',
//             ], 422);
//         }

//         $ticket->update([
//             'current_step' => 'completed',
//             'status' => 'completed',
//             'counter_number' => null,
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Registration completed.',
//             'ticket' => $ticket->fresh(),
//         ]);
//     }

//     public function displayData()
//     {
//         return response()->json([
//             'vitals' => Ticket::where('current_step', 'vitals')
//                 ->where('status', 'serving')
//                 ->orderByDesc('updated_at')
//                 ->get(['ticket_number', 'counter_number']),
//             'registration' => Ticket::where('current_step', 'registration')
//                 ->where('status', 'serving')
//                 ->orderByDesc('updated_at')
//                 ->get(['ticket_number', 'counter_number']),
//             'waiting_vitals' => Ticket::where('current_step', 'triage')
//                 ->where('status', 'waiting')
//                 ->count(),
//             'waiting_registration' => Ticket::where('current_step', 'registration')
//                 ->where('status', 'waiting')
//                 ->count(),
//         ]);
//     }

    
// public function skipVitals($id)
// {
//     $ticket = Ticket::findOrFail($id);

//     if ($ticket->current_step !== 'vitals' || $ticket->status !== 'serving') {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'This ticket is not being served at Vital Signs.',
//         ], 422);
//     }

//     $ticket->update([
//         'status' => 'skipped',
//         'counter_number' => null,
//     ]);

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Ticket marked as skipped. Please follow your facility procedure for absent patients.',
//     ]);
// }

// public function skipRegistration($id)
// {
//     $ticket = Ticket::findOrFail($id);

//     if ($ticket->current_step !== 'registration' || $ticket->status !== 'serving') {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'This ticket is not being served at Registration.',
//         ], 422);
//     }

//     $ticket->update([
//         'status' => 'skipped',
//         'counter_number' => null,
//     ]);

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Ticket marked as skipped. Please follow your facility procedure for absent patients.',
//     ]);

// }
// public function displayData()
// {
//     return response()->json([
//         'vitals' => Ticket::where('current_step', 'vitals')
//             ->where('status', 'serving')
//             ->orderByDesc('updated_at')
//             ->get(['ticket_number', 'counter_number']),
//         'registration' => Ticket::where('current_step', 'registration')
//             ->where('status', 'serving')
//             ->orderByDesc('updated_at')
//             ->get(['ticket_number', 'counter_number']),
//         'waiting_vitals' => Ticket::where('current_step', 'triage')
//             ->where('status', 'waiting')
//             ->count(),
//         'waiting_registration' => Ticket::where('current_step', 'registration')
//             ->where('status', 'waiting')
//             ->count(),
//     ]);
// }


// <!-- <?php

// namespace App\Http\Controllers;

// use App\Models\Ticket;
// use App\Events\TicketUpdated;
// use Illuminate\Http\Request;

// class QueueController extends Controller
// {
//     // 1. Kiosk: Patient selects 'New' or 'Old' and gets a ticket number
//     public function issueTicket(Request $request)
//     {
//         $request->validate([
//             'patient_type' => 'required|in:new,old'
//         ]);

//         // Reset tracking counts daily (e.g., OPD-0001, OPD-0002)
//         $todayCount = Ticket::whereDate('created_at', today())->count() + 1;
//         $ticketNumber = 'OPD-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

//         $ticket = Ticket::create([
//             'ticket_number' => $ticketNumber,
//             'patient_type' => $request->patient_type,
//             'current_step' => 'triage',
//             'status' => 'waiting'
//         ]);

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Ticket issued successfully!',
//             'data' => $ticket
//         ]);
//     }

//     // 2. Vital Signs Booth: Nurse clicks "Call Next Patient"
//     public function nextToVitals(Request $request, $counterNumber)
//     {
//         // Get the oldest waiting patient still at the triage stage
//         $ticket = Ticket::where('current_step', 'triage')
//             ->where('status', 'waiting')
//             ->orderBy('id', 'asc')
//             ->first();

//         if ($ticket) {
//             $ticket->update([
//                 'current_step' => 'vitals',
//                 'status' => 'serving',
//                 'counter_number' => $counterNumber
//             ]);

//             // Broadcast real-time update to Lobby TV monitor
//             event(new TicketUpdated($ticket));

//             return response()->json(['status' => 'success', 'ticket' => $ticket]);
//         }

//         return response()->json(['status' => 'empty', 'message' => 'No patients waiting in Triage.']);
//     }

//     // 3. Vital Signs Booth: Nurse saves vitals data and sends patient to Registration Desk
//     public function completeVitals(Request $request, $id)
//     {
//         $request->validate([
//             'systolic_bp' => 'required|string',
//             'diastolic_bp' => 'required|string',
//             'temperature' => 'required|string',
//         ]);

//         $ticket = Ticket::findOrFail($id);
//         $ticket->update([
//             'systolic_bp' => $request->systolic_bp,
//             'diastolic_bp' => $request->diastolic_bp,
//             'temperature' => $request->temperature,
//             'current_step' => 'registration', // Advance workflow to next department
//             'status' => 'waiting',            // Reset status to wait for front desk
//             'counter_number' => null          // Clear station until called by registrar
//         ]);

//         event(new TicketUpdated($ticket));

//         return response()->json(['status' => 'success', 'message' => 'Vitals saved. Patient routed to Front Desk.']);
//     }

//     // 4. Front Desk: Registrar clicks "Call Next Patient" for Encoding
//     public function nextToRegistration(Request $request, $counterNumber)
//     {
//         // Get the oldest waiting patient who completed their vitals
//         $ticket = Ticket::where('current_step', 'registration')
//             ->where('status', 'waiting')
//             ->orderBy('id', 'asc')
//             ->first();

//         if ($ticket) {
//             $ticket->update([
//                 'status' => 'serving',
//                 'counter_number' => $counterNumber
//             ]);

//             event(new TicketUpdated($ticket));

//             return response()->json(['status' => 'success', 'ticket' => $ticket]);
//         }

//         return response()->json(['status' => 'empty', 'message' => 'No patients waiting in Registration queue.']);
//     }

//     // 5. Front Desk: Complete the entire cycle
//     public function completeRegistration($id)
//     {
//         $ticket = Ticket::findOrFail($id);
//         $ticket->update([
//             'current_step' => 'completed',
//             'status' => 'completed'
//         ]);

//         return response()->json(['status' => 'success', 'message' => 'Patient workflow finished.']);
//     }
// } -->

