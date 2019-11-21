<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancels', function (Blueprint $table) {
            $table->increments('id');
            //todo confirm
            $table->date('cancel_date');
            $table->integer('user_id');
            $table->boolean('is_enable')->default(true);
            $table->text('comment')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->integer('last_update_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cancels');
    }
}
