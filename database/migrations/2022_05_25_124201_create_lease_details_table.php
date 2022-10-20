<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_details', function (Blueprint $table) {
            $table->id();
            $table->integer('lease_id');
            $table->string('lease_document1')->nullable();
            $table->string('lease_document2')->nullable();;
            $table->string('lease_document3')->nullable();;
            $table->string('lease_document4')->nullable();;
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
        Schema::dropIfExists('lease_details');
    }
}
