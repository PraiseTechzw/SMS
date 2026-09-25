<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegrationForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['student_id', 'attendance_date']);
        });

        Schema::table('student_subject_combinations', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('student_subject_combinations', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['subject_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'attendance_date']);
            $table->dropForeign(['student_id']);
        });
    }
}
