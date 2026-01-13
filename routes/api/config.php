<?php

use App\Http\Controllers\Setting\ConfigController;
use Illuminate\Support\Facades\Route;

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: CONFIG MANAGEMENT

Route::get('/',                          [ConfigController::class, 'globalSetting']);
Route::get('/card-text',                 [ConfigController::class, 'getCardText']);
Route::put('/app-config',                [ConfigController::class, 'updateApp'])->middleware('can:view-config-setting');
Route::put('/app-datetime',              [ConfigController::class, 'updateDatetimeFormat'])->middleware('can:view-config-setting');
Route::put('/app-font',                  [ConfigController::class, 'updateFont'])->middleware('can:view-config-setting');