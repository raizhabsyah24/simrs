<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\ActivityLogController;
use App\Http\Controllers\Admin\Apotek\{ObatController, OrderController, AntrianApotekController};
use App\Http\Controllers\Admin\Dokter\PasienListController;
use App\Http\Controllers\Admin\{
    DashboardController,
    LayananController,
    PendaftaranController,
    UserController,
    LabController,
    KasirController,
    RadiologiController,
    TenagaMedisController,
    AntrianController,
    RekammedisController,
    GudangFarmasiController
};
use App\Http\Controllers\Admin\Dokter\{PasienDokterController, DokterController};

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/data', [PasienDokterController::class, 'q']);
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profil-saya', [UserController::class, 'show'])->name('user.profile');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');
});

// HISTORY USER && MANAGEMEN USER
Route::group(['middleware' => ['auth', 'role:super_admin|admin']], function () {
    
    // Layanan
    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');


    // History user
    Route::get('/user/data', [UserController::class, 'index'])
        ->name('data.user');
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
});

//DOKTER
Route::group(['middleware' => ['auth', 'role:dokter|super_admin']], function () {

    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
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
});

// APOTEK
Route::group(['middleware' => ['auth', 'role:apotek | super_admin']], function () {
    // Data obat
    Route::get('/obat', [ObatController::class, 'dataObat'])->name('data');
    Route::get('/obat/fetch-data', [ObatController::class, '_fetchData'])->name('obat.fetchData');
    Route::get('/obat/create', [OrderController::class, 'createObat'])->name('order.create-obat');
    Route::post('/obat/store/obat', [OrderController::class, 'storeObat']);

    // Daftar antrian
    Route::get('/apotek/bpjs', [AntrianApotekController::class, 'index'])->name('data.antrian.bpjs');
    Route::get('/apotek/fetch-data', [AntrianApotekController::class, '_fetchData'])->name('data.antrian');
});


// KASIR
Route::group(['middleware' => ['auth', 'role:kasir|super_admin']], function () {

    Route::get('/bpjs', [KasirController::class, 'kasir_bpjs'])
        ->name('kasir.bpjs');
    Route::get('/umum', [KasirController::class, 'kasir_umum'])
        ->name('kasir.umum');
    Route::get('/otc', [KasirController::class, 'kasir_otc'])
        ->name('kasir.otc');

    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');
});

// LAB
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

// PENDAFTARAN
Route::group(['middleware' => ['auth', 'role:pendaftaran|super_admin']], function () {

    Route::get('/data', [PendaftaranController::class, 'q']);
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
    Route::get('/loket', [AntrianController::class, 'loket'])
    ->name('pendaftaran.loket');
    Route::get('/antrian', [AntrianController::class, 'antrian'])
    ->name('pendaftaran.antrian');
    Route::get('/panggil', [AntrianController::class, 'panggil'])
    ->name('pendaftaran.panggilantrian');
    Route::get('/pendaftaranmessanger', [PendaftaranController::class, 'messanger'])
        ->name('pendaftaran.messanger');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
    ->name('pendaftaran.store');   
    Route::post('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'storePasienSudahPernahDaftar'])
        ->name('pendaftaran.storePasienSudahPernahDaftar');
    Route::delete('/pendaftaran/pasien/{pemeriksaan}/delete', [PendaftaranController::class, 'destroy'])
        ->name('pendaftaran.destroy');
    Route::get('/antrian-umum', [AntrianController::class, 'antrian_umum'])
        ->name('antrian.umum');
    Route::get('/antrian-asuransi', [AntrianController::class, 'antrian_asuransi'])
        ->name('antrian.asuransi');
    Route::get('/antrian-bpjs', [AntrianController::class, 'antrian_bpjs'])
        ->name('antrian.bpjs');
    Route::post('/antrian-umum', [AntrianController::class, 'afo_umum'])
        ->name('afo.umum');
    Route::get('/panggil-lk1', [AntrianController::class, 'loket_1'])
        ->name('panggil.loket1');
    Route::get('/panggil-lk2', [AntrianController::class, 'loket_2'])
        ->name('panggil.loket2');
    Route::get('/panggil-lk3', [AntrianController::class, 'loket_3'])
        ->name('panggil.loket3');
        
});

// Role rekam medis
Route::group(['middleware' => ['auth', 'role:rekam_medis|super_admin']], function () {
    Route::get('/rekam_medis', [RekammedisController::class, 'rekam_medis'])
        ->name('rm.rekammedis');
    Route::get('/retensi', [RekammedisController::class, 'retensi'])
        ->name('rm.retensi');
    Route::get('/migrasi', [RekammedisController::class, 'migrasi_retensi'])
        ->name('rm.migrasi');
});


// RADIOLOGI
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

// ADMIN
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


// POLI
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

// GUDANG
Route::group(['middleware' => ['auth', 'role:gudangfarmasi|super_admin']], function () {

   
    Route::get('/gudang-migrasi', [GudangFarmasiController::class, 'migrasi'])
        ->name('gudang.migrasi');
    Route::get('/gudang-penyimpanan', [GudangFarmasiController::class, 'penyimpanan'])
        ->name('gudang.penyimpanan');
    Route::get('/gudang-po', [GudangFarmasiController::class, 'perencanaan_po'])
        ->name('gudang.po');
        Route::get('/gudang-permintaan-bhp', [GudangFarmasiController::class, 'permintaan_bhp'])
        ->name('gudang.permintaan_bhp');
});

// Route::group(['middleware' => ['auth', 'role:atk|super_admin']], function () {

   
//     Route::get('/gudang-migrasi', [GudangFarmasiController::class, 'migrasi'])
//         ->name('gudang.migrasi');
//     Route::get('/gudang-permintaanbhp', [GudangFarmasiController::class, 'permintaan_bhp'])
//         ->name('gudang.permintaanbhp');
//     Route::get('/gudang-penyimpanan', [GudangFarmasiController::class, 'migrasi'])
//         ->name('gudang.penyimpanan');
//     Route::get('/gudang-po', [GudangFarmasiController::class, 'perencanaan'])
//         ->name('gudang.po');
// });

Route::get('/pasien-list', [PasienController::class, 'data_pasien'])->name('list.pasien');
Route::post('/pasien-store', [PasienController::class, 'store'])->name('store.pasien');

require __DIR__ . '/auth.php';
