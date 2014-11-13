<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeedsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('seeds', function(Blueprint $table) {
            $table->increments('id');
            $table->string('seed');
            $table->string('env');
            $table->integer('batch');
            $table->timestamps();
            $table->index('id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('seeds');
	}

}
