<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Auth\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConfigController extends Controller
{
    public function globalSetting()
    {
        $configApp = Config::where('key', 'app_config')->first();
        $configDatetime = Config::where('key', 'datetime_format')->first();
        $configFont = Config::where('key', 'font')->first();

        return [
            'data' => $configApp
                ? [
                    $configApp->key => $configApp->value,
                    $configDatetime->key => $configDatetime->value,
                    $configFont->key => $configFont->value
                ]
                : []
        ];
    }

    public function getCardText()
    {
        $registerCard = Config::where('key', 'register_card_text')->first();
        $licenseCard = Config::where('key', 'license_card_text')->first();

        return [
            'data' => $registerCard
                ? [
                    $registerCard->key => $registerCard->value,
                    $licenseCard->key => $licenseCard->value,
                ]
                : []
        ];
    }


    public function updateApp(Request $request)
    {
        try {
            $validated = $request->validate([
                'app_name' => 'required|string|max:255',
                'layout' => 'required|in:vertical,horizontal',
                'skin' => 'required|in:default,bordered',
            ]);

            Config::updateOrCreate(
                ['key' => 'app_config'],
                [
                    'value' => $validated,
                    'description' => 'General configuration for the application',
                ]
            );

            return response()->json(['message' => 'Config updated successfully.']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDatetimeFormat(Request $request)
    {
        try {
            $validated = $request->validate([
                'active' => 'required|integer',
            ]);

            DB::transaction(function () use ($validated) {
                DB::table('config')->where('key', 'datetime_format')->delete();

                DB::table('config')->insert([
                    'key' => 'datetime_format',
                    'value' => json_encode([
                        'formats' => [
                            ['id' => 1, 'format' => 'dd-mm-yyyy HH:MM'],
                            ['id' => 2, 'format' => 'dd/mm/yyyy HH:MM'],
                            ['id' => 3, 'format' => 'yyyy-mm-dd HH:MM'],
                        ],
                        'active' => $validated['active'],
                    ]),
                    'description' => 'Supported datetime formats and active selection',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return response()->json([
                'message' => 'Datetime format updated successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateFont(Request $request)
    {
        try {
            $validated = $request->validate([
                'active' => 'required|integer',
            ]);

            DB::transaction(function () use ($validated) {
                DB::table('config')->where('key', 'font')->delete();

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
                        'active' => $validated['active'],
                    ]),
                    'description' => 'Supported fonts and active selection',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return response()->json([
                'message' => 'Font updated successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUserConfig()
    {
        $userConfig = Config::where('key', 'user_field_config')->first();

        return response()->json([
            'data' => $userConfig
        ]);
    }
}
