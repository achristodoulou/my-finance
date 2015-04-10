<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->date('date');
            $table->date('value_date');
            $table->string('description');
            $table->string('amount_credited');
            $table->string('amount_debited');
            $table->string('amount_remaining_balance');
            $table->string('source');
            $table->string('filename');
            $table->string('hash');
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
        Schema::drop('transactions');
    }

}
