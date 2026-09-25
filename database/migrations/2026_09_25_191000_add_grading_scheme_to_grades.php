<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddGradingSchemeToGrades extends Migration
{
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->string('scheme', 30)->nullable()->after('class_type_id');
        });

        DB::table('grades')->whereNull('scheme')->update(['scheme' => 'general']);

        $defaults = [
            ['name' => 'A', 'scheme' => 'zimsec', 'mark_from' => 80, 'mark_to' => 100, 'remark' => 'Excellent'],
            ['name' => 'B', 'scheme' => 'zimsec', 'mark_from' => 70, 'mark_to' => 79, 'remark' => 'Very Good'],
            ['name' => 'C', 'scheme' => 'zimsec', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Credit'],
            ['name' => 'D', 'scheme' => 'zimsec', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Pass'],
            ['name' => 'E', 'scheme' => 'zimsec', 'mark_from' => 40, 'mark_to' => 49, 'remark' => 'Weak Pass'],
            ['name' => 'U', 'scheme' => 'zimsec', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Ungraded'],
            ['name' => 'A', 'scheme' => 'university', 'mark_from' => 80, 'mark_to' => 100, 'remark' => 'Distinction'],
            ['name' => 'B', 'scheme' => 'university', 'mark_from' => 70, 'mark_to' => 79, 'remark' => 'Merit'],
            ['name' => 'C', 'scheme' => 'university', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Credit'],
            ['name' => 'D', 'scheme' => 'university', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Pass'],
            ['name' => 'F', 'scheme' => 'university', 'mark_from' => 0, 'mark_to' => 49, 'remark' => 'Fail'],
        ];

        foreach ($defaults as $default) {
            DB::table('grades')->updateOrInsert(
                ['name' => $default['name'], 'class_type_id' => null, 'scheme' => $default['scheme'], 'remark' => $default['remark']],
                ['mark_from' => $default['mark_from'], 'mark_to' => $default['mark_to'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down()
    {
        DB::table('grades')->whereIn('scheme', ['zimsec', 'university'])->delete();
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('scheme');
        });
    }
}
