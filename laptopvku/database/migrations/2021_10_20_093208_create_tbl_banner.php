<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_banner', function (Blueprint $table) {
            $table->Increments('banner_id');
            $table->string('banner_name');
            $table->string('banner_image');
            $table->integer('banner_status');
            $table->string('banner_desc');


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
        Schema::dropIfExists('tbl_banner');
    }
}
