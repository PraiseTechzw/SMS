<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddZimbabweClassTypes extends Migration
{
    protected $types = [
        ['name' => 'ECD (Early Childhood Development)', 'code' => 'ECD'],
        ['name' => 'Advanced Level', 'code' => 'A'],
        ['name' => 'University Undergraduate', 'code' => 'U'],
        ['name' => 'University Postgraduate', 'code' => 'PG'],
    ];

    public function up()
    {
        foreach ($this->types as $type) {
            DB::table('class_types')->updateOrInsert(
                ['code' => $type['code']],
                ['name' => $type['name'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down()
    {
        DB::table('class_types')
            ->whereIn('code', array_column($this->types, 'code'))
            ->delete();
    }
}
