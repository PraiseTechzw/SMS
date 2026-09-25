<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSubjectCombinationsTable extends Migration
{
    public function up()
    {
        Schema::create('student_subject_combinations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('subject_id');
            $table->string('level', 20);
            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'level']);
            $table->index(['student_id', 'level']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_subject_combinations');
    }
}
