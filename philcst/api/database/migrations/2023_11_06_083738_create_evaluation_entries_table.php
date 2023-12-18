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
      Schema::create('evaluation_entries', function (Blueprint $table) {
          $table->id();
          $table->foreignId('evaluator_id')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

          $table->foreignId('evaluatee_id')
          ->references('id')
          ->on('users')
          ->onUpdate('cascade')
          ->onDelete('cascade');

          $table->foreignId('questionnaire_id')
          ->references('id')
          ->on('questionnaires')
          ->onUpdate('cascade')
          ->onDelete('cascade');

          $table->foreignId('academic_year_id')
          ->references('id')
          ->on('academic_years')
          ->onUpdate('cascade')
          ->onDelete('cascade');
          
          $table->string('comments')->nullable();
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
        Schema::dropIfExists('evaluation_entries');
    }
};
