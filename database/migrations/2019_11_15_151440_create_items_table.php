<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('asset_tag_number');
            $table->integer('staff');
            $table->integer('location_id');
            $table->integer('category_id');
            $table->integer('item_type_id');
            $table->integer('brand_id');
            $table->integer('ownership_id');
            $table->integer('area_id');
            $table->longText('description');
            $table->integer('status_id');
            $table->longText('remarks')->nullable();
            $table->string('date_purchased');
            $table->string('picture')->default('item-default.jpg');
            $table->string('qr_code');
            $table->integer('user_id');
            $table->string('current_person')->nullable();
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
        Schema::dropIfExists('items');
    }
}
