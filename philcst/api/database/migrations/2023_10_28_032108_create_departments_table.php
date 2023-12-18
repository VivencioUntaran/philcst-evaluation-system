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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->enum('department', [
                'College of Criminal Justice Education',
                'College of Hospitality and Tourism Management',
                'College of Accountancy and Business Program',
                'College of Engineering and Architecture',
                'College of Education and Journalism',
                'College of Maritime Studies',
                'College of Computer Studies',
                'Senior High School Dept.',
            ]);
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
        Schema::dropIfExists('departments');
    }
};
