<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\ActivityLogController;
use App\Http\Controllers\Admin\Apotek\{ObatController, OrderController, AntrianApotekController};
use App\Http\Controllers\Admin\{
    DashboardController,
    LayananController,
    PendaftaranController,
    UserController,
    LabController,
    KasirController,
    PoliStationController,
    RadiologiController,
    PosisiPasienController,
    AntrianPoliController
};
use App\Http\Controllers\Admin\Dokter\{PasienDokterController, DokterController};
use App\Http\Controllers\Auth\UserDetailController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profil-saya', [UserDetailController::class, 'show'])->name('user.profile');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    Route::get('/antrian-pasien/poli-jantung', [AntrianPoliController::class, 'antrianPoliJantung'])
    ->name('dashboard.antrian-poli.jantung');
    Route::get('/antrian-pasien/poli-jantung/data', [AntrianPoliController::class, 'dataAntrianPoliJantung'])
        ->name('dashboard.antrian-poli.jantung.data');
    Route::get('/antrian-pasien/poli-anak', [AntrianPoliController::class, 'antrianPoliAnak'])
        ->name('dashboard.antrian-poli.anak');
});

// Role super admin
Route::group(['middleware' => ['auth', 'role:super_admin|apotek|dokter|pendaftaran']], function () {

    // Layanan
    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');

    // dokter
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/dokter/fetch-data', [DokterController::class, 'fetchData'])->name('dokter.fetchData');
    Route::get('/dokter/create', [DokterController::class, 'create'])->name('dokter.create');
    Route::get('/dokter/{dokter}/edit', [DokterController::class, 'edit'])->name('dokter.edit');
    Route::get('/dokter/{dokter}/ganti-jadwal-praktek', [DokterController::class, 'gantiJadwal'])->name('dokter.ganti-jadwal-praktek');
    Route::put('/dokter/{dokter}/ganti-jadwal-praktek', [DokterController::class, 'updateJadwal'])->name('dokter.update-jadwal-praktek');
    Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store');
    Route::put('/dokter/{dokter}', [DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/dokter/{dokter}/delete', [DokterController::class, 'delete'])->name('dokter.delete');

    // History user
    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');


    // Pendaftaran
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
        ->name('pendaftaran.index');
    Route::get('/pendaftaran/fetch-data', [PendaftaranController::class, 'fetchData'])
        ->name('pendaftaran.fetchData');
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])
        ->name('pendaftaran.create');
    Route::get('/pendaftaran/dokter-poli', [PendaftaranController::class, 'getDokterPoli'])
        ->name('pendaftaran.dokter-poli');
    Route::get('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'createPasienSudahPernahDaftar'])
        ->name('pendaftaran.createPasienSudahPernahDaftar');
    Route::get('/pendaftaran/cari-pasien', [PendaftaranController::class, 'searchPasien'])
        ->name('pendaftaran.search-pasien');
    Route::get('/pendaftaran/change-pasien', [PendaftaranController::class, 'changePasien'])
        ->name('pendaftaran.change-pasien');


    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
        ->name('pendaftaran.store');
    Route::post('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'storePasienSudahPernahDaftar'])
        ->name('pendaftaran.storePasienSudahPernahDaftar');

    Route::delete('/pendaftaran/pasien/{pemeriksaan}/delete', [PendaftaranController::class, 'destroy'])
        ->name('pendaftaran.destroy');

    Route::get('/user', [UserController::class, 'index'])
        ->name('user.index');
    Route::get('/user/fetch-data', [UserController::class, 'fetchData'])
        ->name('user.fetchData');
    Route::get('/user/create', [UserController::class, 'create'])
        ->name('user.create');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])
        ->name('user.edit');
    Route::post('/user/store', [UserController::class, 'store'])
        ->name('user.store');
    Route::put('/user/{user}/update-status', [UserController::class, 'updateStatus'])
        ->name('user.update-status');
    Route::put('/user/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('user.reset-password');
    Route::put('/user/{user}/update', [UserController::class, 'update'])
        ->name('user.update');
    Route::delete('/user/{user}/delete', [UserController::class, 'delete'])
        ->name('user.delete');


    // Posisi pasien
    Route::get('/pendaftaran/{id}/posisi-pasien', [PosisiPasienController::class, 'rajal'])
        ->name('posisi-pasien.rajal');
});

// Role dokter
Route::group(['middleware' => ['auth', 'role:dokter|super_admin']], function () {
    Route::get('/dokter/daftar-pasien', [PasienDokterController::class, 'index'])
        ->name('dokter.daftar-pasien');
    Route::get('/dokter/daftar-pasien/fetch', [PasienDokterController::class, 'fetch'])
        ->name('dokter.daftar-pasien.fetch');
    Route::get('/dokter/periksa-pasien/{id}', [PasienDokterController::class, 'periksaPasien'])
        ->name('dokter-spesialis.periksa-pasien');
    Route::get('/dokter/search-obat', [PasienDokterController::class, 'searchObat'])
        ->name('dokter.search-obat');
    Route::post('/dokter/change-obat', [PasienDokterController::class, 'changeObat'])
        ->name('dokter.change-obat');
    Route::get('/dokter/obat-pasien/{id}', [PasienDokterController::class, 'obatPasien'])
        ->name('dokter.obat-pasien');
    Route::put('/dokter/obat-pasien/update-quantity/{id}', [PasienDokterController::class, 'updateQuantity'])
        ->name('dokter.obat-pasien.update-quantity');
    Route::delete('/dokter/hapus-obat/{id}', [PasienDokterController::class, 'hapusObat'])
        ->name('dokter.obat-pasien.hapus');
    Route::put('/dokter/daftar-pasien/{periksaDokter}', [PasienDokterController::class, 'storePasien'])
        ->name('dokter.store-pasien');
    Route::put('/dokter/obat-pasien/signa-1/{id}', [PasienDokterController::class, 'signa1'])
        ->name('dokter.obat-pasien.signa1');
    Route::put('/dokter/obat-pasien/signa-2/{id}', [PasienDokterController::class, 'signa2'])
        ->name('dokter.obat-pasien.signa2');

    // Diagnosa
    Route::get('/dokter/search-diagnosa', [PasienDokterController::class, 'searchDiagnosa'])
        ->name('dokter.search-diagnosa');
    Route::get('/dokter/diagnosa-pasien/{id}', [PasienDokterController::class, 'diagnosaPasien'])
        ->name('dokter.diagnosa-pasien');
    Route::post('/dokter/change-diagnosa', [PasienDokterController::class, 'changeDiagnosa'])
        ->name('dokter.change-diagnosa');
    Route::delete('/dokter/hapus-diagnosa/{diagnosaPasienRajal}', [PasienDokterController::class, 'hapusDiagnosa'])
        ->name('dokter.diagnosa-pasien.hapus');
    Route::put('/dokter/diagnosa-pasien/{diagnosaPasienRajal}', [PasienDokterController::class, 'diagnosaBagian'])
        ->name('dokter.diagnosa-pasien.bagian');

    // Tindakan
    Route::get('/dokter/search-tindakan', [PasienDokterController::class, 'searchTindakan'])
        ->name('dokter.search-tindakan');
    Route::get('/dokter/tindakan-pasien/{id}', [PasienDokterController::class, 'tindakanPasien'])
        ->name('dokter.tindakan-pasien');
    Route::post('/dokter/change-tindakan', [PasienDokterController::class, 'changeTindakan'])
        ->name('dokter.change-tindakan');
    Route::delete('/dokter/hapus-tindakan/{tindakanPasienRajal}', [PasienDokterController::class, 'hapustindakan'])
        ->name('dokter.tindakan-pasien.hapus');
    Route::put('/dokter/tindakan-pasien/{tindakanPasienRajal}', [PasienDokterController::class, 'tindakanBagian'])
        ->name('dokter.tindakan-pasien.bagian');
});

// Role apotek
Route::group(['middleware' => ['auth', 'role:apotek']], function () {
    // Data obat
    Route::get('/obat', [ObatController::class, 'dataObat'])->name('data');
    Route::get('/obat/fetch-data', [ObatController::class, '_fetchData'])->name('obat.fetchData');
    Route::get('/create', [OrderController::class, 'createObat'])->name('order.create-obat');
    Route::post('/create', [OrderController::class, 'storeObat'])->name('order.store.obat');

    // Daftar antrian
    Route::get('/antrian-apotek/bpjs', [AntrianApotekController::class, 'index'])->name('data.antrian');
});

// Role poli
Route::group(['middleware' => ['auth', 'role:poli_station|super_admin']], function () {
    Route::get('/poli-station', [PoliStationController::class, 'index'])
        ->name('poli-station.index');
    Route::get('/poli-station/fetch-data', [PoliStationController::class, 'fetchData'])
        ->name('poli-station.fetchData');
    Route::get('/poli-station/{periksaPoliStation}/detail', [PoliStationController::class, 'show'])
        ->name('poli-station.detail-pasien');
    Route::post('/poli-station/{pemeriksaan}/periksa', [PoliStationController::class, 'periksa'])
        ->name('poli-station.periksa');
    Route::put('/poli-station/{periksaPoliStation}/update', [PoliStationController::class, 'update'])
        ->name('poli-station.update');
});

Route::group(['middleware' => ['auth', 'role:apotek']], function () {

    Route::get('/obat', [ObatController::class, 'dataObat'])->name('data');
    Route::get('/obat/fetch-data', [ObatController::class, 'fetchData'])->name('obat.fetchData');
    Route::get('/create', [OrderController::class, 'createObat'])->name('order.create-obat');
    Route::post('/create', [OrderController::class, 'storeObat'])->name('order.store.obat');
});

Route::group(['middleware' => ['auth', 'role:kasir|super_admin']], function () {
    Route::get('/kasir', [KasirController::class, 'index'])
        ->name('kasir.index');
    Route::get('/kasir/fetch-data', [KasirController::class, 'fetch'])
        ->name('kasir.fetch');
    Route::get('/kasir/{kasir}', [KasirController::class, 'show'])
        ->name('kasir.show');
    Route::get('/kasir/{kasir}/proses', [KasirController::class, 'proses'])
        ->name('kasir.proses');
    Route::put('/kasir/{kasir}/update-tagihan', [KasirController::class, 'updateTagihan'])
        ->name('kasir.update-tagihan');
    Route::put('/kasir/{kasir}/update-status', [KasirController::class, 'updateStatus'])
        ->name('kasir.update-status');
});

Route::group(['middleware' => ['auth', 'role: lab|super_admin']], function () {


    Route::get('/otclab', [LabController::class, 'lab_otc'])
        ->name('lab.otc');
    Route::get('/umumlab', [LabController::class, 'lab_umum'])
        ->name('lab.umum');

    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');
});

Route::group(['middleware' => ['auth', 'role:radiologi|super_admin']], function () {

    Route::get('/otcradio', [RadiologiController::class, 'radiologi_otc'])
        ->name('order.radiologi-otc');
    Route::get('/umumradio', [RadiologiController::class, 'radiologi_'])
        ->name('order.radiologi-umum');
    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');
});


Route::group(['middleware' => ['auth', 'role:dokter|super_admin']], function () {

    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');
});

Route::group(['middleware' => ['auth', 'role:admin|super_admin']], function () {

    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');

    Route::get('/user/medis', [TenagaMedisController::class, 'dataMedis'])
        ->name('data.medis');
    Route::get('/user/medis/create', [TenagaMedisController::class, 'createMedis'])
        ->name('data.create-medis');
    Route::post('/user/medis/store', [TenagaMedisController::class, 'storeMedis'])
        ->name('data.store-medis');
});

Route::group(['middleware' => ['auth', 'role:poli|super_admin']], function () {

    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');
});

Route::get('/pasien-list', [PasienController::class, 'data_pasien'])->name('list.pasien');
Route::post('/pasien-store', [PasienController::class, 'store'])->name('store.pasien');

require __DIR__ . '/auth.php';
