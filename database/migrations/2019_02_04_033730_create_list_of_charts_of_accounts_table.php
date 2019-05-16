<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListOfChartsOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_of_charts_of_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('account_list',25);
            $table->integer('account_no');
            $table->char('account_title',50);
            $table->text('account_description');
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
        Schema::dropIfExists('list_of_charts_of_accounts');
    }
}
