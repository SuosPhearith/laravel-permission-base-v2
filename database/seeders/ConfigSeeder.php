<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert general application configuration
        DB::table('config')->insert([
            'key' => 'app_config',
            'value' => json_encode([
                'app_name' => 'LIS',
                'layout' => 'horizontal',
                'skin' => 'default',
            ]),
            'description' => 'General configuration for the application',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert datetime format configuration
        DB::table('config')->insert([
            'key' => 'datetime_format',
            'value' => json_encode([
                'formats' => [
                    ['id' => 1, 'format' => 'dd-mm-yyyy HH:MM'],
                    ['id' => 2, 'format' => 'dd/mm/yyyy HH:MM'],
                    ['id' => 3, 'format' => 'yyyy-mm-dd HH:MM'],
                ],
                'active' => 1, // refer to format by ID
            ]),
            'description' => 'Supported datetime formats and active selection',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert datetime format configuration
        DB::table('config')->insert([
            'key' => 'font',
            'value' => json_encode([
                'fonts' => [
                    ['id' => 1, 'name' => 'Siemreap'],
                    ['id' => 2, 'name' => 'Noto Sans Khmer'],
                    ['id' => 3, 'name' => 'Hanuman'],
                    ['id' => 4, 'name' => 'Suwannaphum'],
                    ['id' => 5, 'name' => 'Nokora'],
                ],
                'active' => 1
            ]),
            'description' => 'Supported fonts and active selection',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
