<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('resident_name');
            $table->string('resident_id');
            $table->string('document_type');
            $table->string('purpose');
            $table->text('notes')->nullable();
            $table->enum('status', ['approved', 'ready_to_pickup'])->default('approved');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('applications');
    }
};