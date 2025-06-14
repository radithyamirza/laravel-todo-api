<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('assignee')->nullable();
            $table->date('due_date');
            $table->float('time_tracked')->default(0);
            $table->enum('status', ['pending', 'open', 'in_progress', 'completed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('todos');
    }
};