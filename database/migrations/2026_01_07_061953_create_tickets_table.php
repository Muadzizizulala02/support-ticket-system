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
        // By default, if your Model is named Ticket, Laravel looks for a table named tickets (plural).
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // id that connects ticket to user
            // Column name must be <singular_table_name>_id. (e.g., user_id for users table)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
