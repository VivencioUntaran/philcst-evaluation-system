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
        Schema::create('criteria_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')
                ->references('id')
                ->on('criterias')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('label');
            $table->float('point');
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
        Schema::dropIfExists('criteria_points');
    }
};
