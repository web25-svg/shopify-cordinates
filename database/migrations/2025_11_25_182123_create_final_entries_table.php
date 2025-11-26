<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('final_entries', function (Blueprint $table) {
            $table->id();
            $table->string('player_id');
            $table->string('product_id');
            $table->string('order_id');
            $table->string('customer_email');
            $table->double('x');
            $table->double('y');
            // $table->integer('entries_count');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('final_entries');
    }
};
