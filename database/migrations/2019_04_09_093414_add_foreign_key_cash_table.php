<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_departs', function (Blueprint $table) {
            $table->foreign('manager_id')
                ->references('id')
                ->on('managers')
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
        Schema::table('cash_departs', function (Blueprint $table) {
            $table->dropForeign('cash_departs_manager_id_foreign');
        });
    }
}
