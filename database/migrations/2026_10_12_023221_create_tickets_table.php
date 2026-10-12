<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // e.g., OPD-0001
            $table->enum('patient_type', ['new', 'old'])->default('new');
            
            // Workflow: triage -> vitals tracking -> front desk registration -> completed
            $table->enum('current_step', ['triage', 'vitals', 'registration', 'completed'])->default('triage');
            $table->enum('status', ['waiting', 'serving', 'completed', 'skipped'])->default('waiting');
            
            $table->integer('counter_number')->nullable(); // Assigned booth or desk number
            $table->string('systolic_bp')->nullable();
            $table->string('diastolic_bp')->nullable();
            $table->string('temperature')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
