<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaseResidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_residents', function (Blueprint $table) {
            $table->id();
            $table->integer('lease_id');
            $table->string('resident_name')->nullable();;
            $table->string('resident_relation')->nullable();;
            $table->string('resident_information')->nullable();;
            $table->foreign('lease_id')->references('id')->on('leases');
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
        Schema::dropIfExists('lease_residents');
    }
}
