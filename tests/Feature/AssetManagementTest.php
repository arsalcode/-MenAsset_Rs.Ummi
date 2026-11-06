<?php

namespace Tests\Feature;

use App\Livewire\AssetManager;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use DatabaseTransactions;
    public function test_dashboard_page_loads_successfully_and_displays_hospital_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('RS UMMI BOGOR');
        $response->assertSee('Sistem Manajemen Aset');
        $response->assertSee('Kelola Aset');
    }

    public function test_report_tab_displays_financial_cards(): void
    {
        Livewire::test(AssetManager::class)
            ->call('setTab', 'tabReports')
            ->assertSee('Total Inventaris Aset RS')
            ->assertSee('Estimasi Nilai Inventaris');
    }

    public function test_livewire_component_renders_and_lists_assets(): void
    {
        $latestAsset = Asset::orderBy('created_at', 'desc')->first();

        Livewire::test(AssetManager::class)
            ->assertStatus(200)
            ->assertSee($latestAsset->asset_code)
            ->assertSee($latestAsset->name);
    }

    public function test_search_filter_narrows_down_results(): void
    {
        Livewire::test(AssetManager::class)
            ->set('search', 'Defibrillator')
            ->assertSee('MED-001')
            ->assertDontSee('Bed Pasien Elektrik');
    }

    public function test_can_create_new_asset_via_livewire(): void
    {
        $category = Category::first();
        $location = Location::first();

        $code = 'TEST-ASSET-' . uniqid();

        Livewire::test(AssetManager::class)
            ->call('openCreateModal')
            ->set('asset_code', $code)
            ->set('name', 'Alat Uji Coba Medis Livewire')
            ->set('category_id', $category->id)
            ->set('location_id', $location->id)
            ->set('brand', 'Philips Healthcare')
            ->set('model_type', 'IntelliVue MP70')
            ->set('purchase_cost', 85000000)
            ->set('condition', 'baik')
            ->set('status', 'aktif')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('isFormOpen', false);

        $this->assertDatabaseHas('assets', [
            'asset_code' => $code,
            'name' => 'Alat Uji Coba Medis Livewire',
        ]);
    }

    public function test_validation_prevents_empty_asset_submission(): void
    {
        Livewire::test(AssetManager::class)
            ->call('openCreateModal')
            ->set('category_id', '')
            ->set('asset_code', '')
            ->set('name', '')
            ->set('location_id', '')
            ->call('save')
            ->assertHasErrors(['asset_code', 'name', 'category_id', 'location_id']);
    }

    public function test_can_view_detail_modal_with_maintenance_history(): void
    {
        $asset = Asset::where('asset_code', 'MED-001')->first();

        Livewire::test(AssetManager::class)
            ->call('openDetailModal', $asset->id)
            ->assertSet('isDetailOpen', true)
            ->assertSee('Histori Kalibrasi');
    }
}
