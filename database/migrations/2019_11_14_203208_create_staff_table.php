<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('location_id');
            $table->string('registration_number');
            $table->string('staff_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_name')->nullable();
            $table->string('dob',20);
            $table->string('gender',15);
            $table->string('phone_number',20);
            $table->string('personal_email');
            $table->string('work_email');
            $table->string('joining_date',20);
            $table->string('contract_valid_till',20);
            $table->string('profile',25);
            $table->string('qr_code',25);
            $table->boolean('can_login')->default(0);
            $table->string('remarks',500)->default('No remarks')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
