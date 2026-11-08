<?php

namespace Tests\Feature;

use App\Livewire\FieldAssetManager;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FieldAssetManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_field_portal_page_loads_successfully(): void
    {
        $response = $this->get('/lapangan');

        $response->assertStatus(200);
        $response->assertSee('RS UMMI BOGOR');
        $response->assertSee('Portal Teknisi');
        $response->assertSee('Pemindai Stiker QR Alat Medis');
    }

    public function test_field_tabs_navigation_switches_views(): void
    {
        Livewire::test(FieldAssetManager::class)
            ->assertSet('activeTab', 'scan')
            ->call('setTab', 'list')
            ->assertSet('activeTab', 'list')
            ->assertSee('Semua Ruangan')
            ->call('setTab', 'create')
            ->assertSet('activeTab', 'create')
            ->assertSee('Input Alat Medis Baru');
    }

    public function test_can_auto_generate_asset_code_based_on_category(): void
    {
        $category = Category::where('code', 'MED')->first();

        Livewire::test(FieldAssetManager::class)
            ->set('category_id', $category->id)
            ->call('generateAssetCode')
            ->assertSet('asset_code', function ($code) {
                return str_starts_with($code, 'MED-');
            });
    }

    public function test_field_staff_can_scan_existing_qr_code_to_view_detail(): void
    {
        $asset = Asset::first();

        Livewire::test(FieldAssetManager::class)
            ->call('handleScannedCode', $asset->asset_code)
            ->assertSet('selectedAssetId', $asset->id)
            ->assertSet('isDetailOpen', true)
            ->assertSee($asset->name);
    }

    public function test_scanning_unregistered_code_redirects_to_create_form_with_code_prefilled(): void
    {
        $newCode = 'NEW-ALAT-999';

        Livewire::test(FieldAssetManager::class)
            ->call('handleScannedCode', $newCode)
            ->assertSet('activeTab', 'create')
            ->assertSet('asset_code', $newCode);
    }

    public function test_field_staff_can_create_new_asset_from_mobile_form(): void
    {
        $category = Category::first();
        $location = Location::first();
        $code = 'FLD-' . uniqid();

        Livewire::test(FieldAssetManager::class)
            ->set('activeTab', 'create')
            ->set('asset_code', $code)
            ->set('name', 'Tensimeter Digital Mobile Lapangan')
            ->set('category_id', $category->id)
            ->set('location_id', $location->id)
            ->set('brand', 'Omron')
            ->set('model_type', 'HEM-7120')
            ->set('condition', 'baik')
            ->set('status', 'aktif')
            ->call('saveAsset')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('assets', [
            'asset_code' => $code,
            'name' => 'Tensimeter Digital Mobile Lapangan',
        ]);
    }

    public function test_field_staff_can_submit_defect_report(): void
    {
        $asset = Asset::first();

        Livewire::test(FieldAssetManager::class)
            ->call('openDetail', $asset->id)
            ->call('openReportModal')
            ->set('report_condition', 'rusak_ringan')
            ->set('report_notes', 'Kabel sensor mengalami korsleting dan monitor kedip-kedip.')
            ->set('report_technician', 'Ns. Ahmad Lapangan')
            ->call('submitDefectReport')
            ->assertHasNoErrors()
            ->assertSet('isReportModalOpen', false);

        $asset->refresh();
        $this->assertEquals('rusak_ringan', $asset->condition);
        $this->assertEquals('pemeliharaan', $asset->status);

        $this->assertDatabaseHas('maintenance_logs', [
            'asset_id' => $asset->id,
            'status' => 'dalam_proses',
            'notes' => 'Laporan Lapangan: Kabel sensor mengalami korsleting dan monitor kedip-kedip.',
        ]);
    }
}

