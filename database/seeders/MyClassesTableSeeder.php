<?php
namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MyClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('my_classes')->delete();
        $ct = ClassType::pluck('id', 'code');

        $data = [
            ['name' => 'ECD A', 'class_type_id' => $ct['ECD']],
            ['name' => 'ECD B', 'class_type_id' => $ct['ECD']],
            ['name' => 'Grade 1', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 2', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 3', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 4', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 5', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 6', 'class_type_id' => $ct['P']],
            ['name' => 'Grade 7', 'class_type_id' => $ct['P']],
            ['name' => 'Form 1', 'class_type_id' => $ct['J']],
            ['name' => 'Form 2', 'class_type_id' => $ct['J']],
            ['name' => 'Form 3', 'class_type_id' => $ct['S']],
            ['name' => 'Form 4', 'class_type_id' => $ct['S']],
            ['name' => 'Lower 6', 'class_type_id' => $ct['A']],
            ['name' => 'Upper 6', 'class_type_id' => $ct['A']],
            ['name' => 'Undergraduate', 'class_type_id' => $ct['U']],
            ['name' => 'Postgraduate', 'class_type_id' => $ct['PG']],
            ];

        DB::table('my_classes')->insert($data);

    }
}
