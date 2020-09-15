<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{

    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('href')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->string('group')->default('main');
            $table->integer('order')->default(0);
            $table->string('permission')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
