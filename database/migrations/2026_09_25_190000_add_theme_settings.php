<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddThemeSettings extends Migration
{
    protected $settings = [
        ['type' => 'theme_primary', 'description' => '#18283f'],
        ['type' => 'theme_secondary', 'description' => '#101c30'],
        ['type' => 'theme_accent', 'description' => '#16b8a6'],
        ['type' => 'theme_accent_dark', 'description' => '#0f9b90'],
    ];

    public function up()
    {
        foreach ($this->settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['type' => $setting['type']],
                ['description' => $setting['description'], 'updated_at' => now()]
            );
        }
    }

    public function down()
    {
        DB::table('settings')
            ->whereIn('type', array_column($this->settings, 'type'))
            ->delete();
    }
}
