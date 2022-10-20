<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->date('lease_date');
            $table->integer('project_id');
            $table->integer('unit_id');
            $table->integer('resident_id');
            $table->enum('status_of_account', ['Y','N']);
            $table->enum('amenity', ['Y','N']);
            $table->string('lease_type')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('project_id')->references('id')->on('projects');
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
        Schema::dropIfExists('leases');
    }
}
