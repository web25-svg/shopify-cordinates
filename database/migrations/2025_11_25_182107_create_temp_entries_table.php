<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_entries', function (Blueprint $table) {
            $table->id();
            $table->string('session_token');
            $table->string('product_id');
            $table->double('x');
            $table->double('y');
            // $table->integer('entries_count');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_entries');
    }
};
