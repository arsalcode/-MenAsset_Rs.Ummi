<?php

namespace Tests\Feature;

use App\Livewire\FieldAssetManager;
use App\Models\Asset;
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
        $response->assertSee('Portal Lapangan');
        $response->assertSee('Kamera Pemindai QR Stiker');
    }

    public function test_field_tabs_navigation_switches_views(): void
    {
        Livewire::test(FieldAssetManager::class)
            ->assertSet('activeTab', 'scan')
            ->call('setTab', 'room')
            ->assertSet('activeTab', 'room')
            ->assertSee('Pilih Ruangan Pemeriksaan')
            ->call('setTab', 'reports')
            ->assertSet('activeTab', 'reports')
            ->assertSee('Pusat Pantau Tiket Laporan Kerusakan');
    }

    public function test_field_staff_can_scan_existing_qr_code_to_view_detail(): void
    {
        $asset = Asset::first();

        Livewire::test(FieldAssetManager::class)
            ->call('handleScannedCode', $asset->asset_code)
            ->assertSet('selectedAssetId', $asset->id)
            ->assertSee($asset->name);
    }

    public function test_scanning_unregistered_code_shows_danger_alert(): void
    {
        $newCode = 'UNKNOWN-ALAT-999';

        Livewire::test(FieldAssetManager::class)
            ->call('handleScannedCode', $newCode)
            ->assertSet('selectedAssetId', null)
            ->assertSet('feedbackType', 'danger');
    }

    public function test_field_staff_can_confirm_normal_condition_checklist(): void
    {
        $asset = Asset::first();

        Livewire::test(FieldAssetManager::class)
            ->call('confirmNormalCondition', $asset->id)
            ->assertSet('feedbackType', 'success');

        $asset->refresh();
        $this->assertEquals('baik', $asset->condition);
        $this->assertEquals('aktif', $asset->status);

        $this->assertDatabaseHas('maintenance_logs', [
            'asset_id' => $asset->id,
            'type' => 'pemeliharaan_rutin',
            'status' => 'selesai',
        ]);
    }

    public function test_field_staff_can_submit_defect_report(): void
    {
        $asset = Asset::first();

        Livewire::test(FieldAssetManager::class)
            ->call('openReportModal', $asset->id)
            ->set('report_condition', 'rusak_ringan')
            ->set('report_notes', 'Kabel sensor mengalami korsleting dan monitor kedip-kedip.')
            ->set('report_reporter', 'Ns. Ahmad (Ruang IGD)')
            ->call('submitDefectReport')
            ->assertHasNoErrors()
            ->assertSet('isReportModalOpen', false)
            ->assertSet('isSuccessModalOpen', true)
            ->assertSee('Laporan Kerusakan Berhasil Dikirim!')
            ->call('closeSuccessModal')
            ->assertSet('isSuccessModalOpen', false);

        $asset->refresh();
        $this->assertEquals('rusak_ringan', $asset->condition);
        $this->assertEquals('pemeliharaan', $asset->status);

        $this->assertDatabaseHas('maintenance_logs', [
            'asset_id' => $asset->id,
            'status' => 'dalam_proses',
            'notes' => 'Laporan Lapangan: Kabel sensor mengalami korsleting dan monitor kedip-kedip.',
            'vendor_technician' => 'Ns. Ahmad (Ruang IGD)',
        ]);
    }

    public function test_field_staff_can_filter_assets_by_room(): void
    {
        $location = Location::has('assets')->first() ?? Location::first();

        Livewire::test(FieldAssetManager::class)
            ->set('activeTab', 'room')
            ->set('selectedLocationId', (string) $location->id)
            ->assertSee($location->name);
    }
}
