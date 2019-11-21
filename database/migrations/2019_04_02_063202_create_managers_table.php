<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable_ip');
            $table->integer('user_id')->unsigned();
            $table->text('memo');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('managers', function(Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers', function(Blueprint $table) {
            $table->dropForeign('managers_user_id_foreign');
        });

        Schema::dropIfExists('managers');
    }
}
