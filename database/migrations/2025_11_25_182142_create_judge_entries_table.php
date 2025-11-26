<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('judge_entries', function (Blueprint $table) {
            $table->id();
            $table->string('judge_id');
            $table->foreignId('product_id')->constrained('final_entries')->onDelete('cascade');
            $table->double('x');
            $table->double('y');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('judge_entries');
    }
};
