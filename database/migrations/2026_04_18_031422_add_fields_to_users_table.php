<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('resident_id')->nullable()->after('name');
            $table->string('first_name')->nullable()->after('resident_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->enum('role', ['resident', 'staff'])->default('resident')->after('last_name');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['resident_id', 'first_name', 'last_name', 'role']);
        });
    }
};