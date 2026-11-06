<?php

use App\Livewire\AssetManager;
use Illuminate\Support\Facades\Route;

Route::get('/', AssetManager::class)->name('dashboard');
