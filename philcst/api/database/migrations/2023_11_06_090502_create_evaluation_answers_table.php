<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_entry_id')
              ->references('id')
              ->on('evaluation_entries')
              ->onUpdate('cascade')
              ->onDelete('cascade');
              
            $table->foreignId('criteria_id')
              ->references('id')
              ->on('criterias')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->foreignId('question_id')
              ->references('id')
              ->on('questions')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->foreignId('choice_id')
              ->references('id')
              ->on('choices')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_answers');
    }
};
