<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('registration_number')->unique()->after('event_id');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending')->after('registration_number');
            $table->integer('ticket_quantity')->default(1)->after('status');
            $table->decimal('total_amount', 10, 2)->after('ticket_quantity');
            $table->text('special_requests')->nullable()->after('total_amount');

            // Prevent multiple registrations for the same event by the same user
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('registration_number');
            $table->dropColumn('status');
            $table->dropColumn('ticket_quantity');
            $table->dropColumn('total_amount');
            $table->dropColumn('special_requests');
            $table->dropUnique(['event_id', 'user_id']);
        });
    }
};
