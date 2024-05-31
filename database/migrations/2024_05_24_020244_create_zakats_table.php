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
        Schema::create('zakats', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->enum('gender',['laki-laki','perempuan']);
            $table->string('phone');
            $table->string('email');

            $table->double('amount');
            $table->enum('type', ['penghasilan', 'maal']);
            $table->date('date');

            $table->string('penerima')->nullable();

            $table->enum('status',['pending','diterima','disalurkan'])->default('pending');

            $table->string('midtrans_token');

            $table->unsignedBigInteger('approver_id')->nullable();
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakats');
    }
};
