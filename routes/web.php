<?php

use App\Livewire\AssetManager;
use App\Livewire\FieldAssetManager;
use Illuminate\Support\Facades\Route;

// Mode Dashboard Utama SIMRS (Admin & Manajemen)
Route::get('/', AssetManager::class)->name('dashboard');

// Mode Lapangan & Mobile Teknisi Ruangan (Field Staff)
Route::get('/lapangan', FieldAssetManager::class)->name('field');
