<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->foreignId('employee_id');
            $table->foreignId('rate_id');
            $table->string('plate_number')->nullable();
            $table->dateTime('enter_at');
            $table->dateTime('exit_at')->nullable();
            $table->integer('total_hour')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('total_paid')->nullable();
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users');
            $table->foreign('rate_id')->references('id')->on('rates');
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
};
