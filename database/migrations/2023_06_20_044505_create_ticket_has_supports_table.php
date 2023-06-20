<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_has_supports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->text('instructions');
            $table->enum('reply_by', ['ADMIN', 'CUSTOMER']);
            $table->enum('ticket_status', ['Pending', 'Process' , 'Solved' , 'Closed']);
            $table->timestamps();

            $table->index(['ticket_id']);
            $table->foreign('ticket_id')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_has_supports');
    }
};
