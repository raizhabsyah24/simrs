<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\ActivityLogController;
use App\Http\Controllers\Admin\Apotek\{ObatController, OrderController, AntrianBpjsController, AntrianUmumController, Select2Controller,};
use App\Http\Controllers\Admin\Dokter\PasienDokterController;
use App\Http\Controllers\Admin\{
    DashboardController,
    LayananController,
    PendaftaranController,
    UserController,
    LabController,
    KasirController,
    RadiologiController,
    TenagaMedisController,
    RekammedisController
};

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/data', [PasienDokterController::class, 'q']);
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profil-saya', [UserController::class, 'show'])->name('user.profile');
});

// Role super admin
Route::group(['middleware' => ['auth', 'role:super_admin|apotek|dokter|poli|pendaftaran|rekam_medis']], function () {
    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan.index');
    Route::get('/layanan/fetch-data', [LayananController::class, 'fetchData'])
        ->name('layanan.fetchData');
    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');

    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');

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
    // Route::get('/messanger', [PendaftaranController::class, 'messanger'])
    //     ->name('pendaftaran.messanger');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
        ->name('pendaftaran.store');
    Route::post('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'storePasienSudahPernahDaftar'])
        ->name('pendaftaran.storePasienSudahPernahDaftar');

    // Daftar managemen user
    Route::get('/user/data', [UserController::class, 'index'])
        ->name('data.user');
    Route::get('/user/fetch-data', [UserController::class, 'fetchData'])
        ->name('user.fetchData');
    Route::get('/user/create', [UserController::class, 'createUser'])
        ->name('user.create');
    Route::post('/user/store', [UserController::class, 'storeUser'])
        ->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'editUser'])
        ->name('user.edit');
    Route::put('/user/{id}/update', [UserController::class, 'updateUser'])
        ->name('user.update');

    // Daftar tenaga medis
    Route::get('/user/medis', [TenagaMedisController::class, 'dataMedis'])
        ->name('data.medis');
    Route::get('/user/medis/create', [TenagaMedisController::class, 'createMedis'])
        ->name('data.create-medis');
    Route::post('/user/medis/store', [TenagaMedisController::class, 'storeMedis'])
        ->name('data.store-medis');
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
});

// Role apotek
Route::group(['middleware' => ['auth', 'role:apotek|super_admin']], function () {
    // Data obat
    Route::get('/obat', [ObatController::class, 'dataObat'])->name('data');
    Route::get('/obat/fetch-data', [ObatController::class, '_fetchData'])->name('obat.fetchData');
    Route::get('/obat/create', [OrderController::class, 'createObat'])->name('order.create-obat');
    Route::post('/obat/store/obat', [OrderController::class, 'storeObat']);

    // Daftar antrian bpjs
    Route::get('/apotek/bpjs', [AntrianBpjsController::class, 'index'])->name('data.antrian.bpjs');
    Route::get('/apotek/fetch-data', [AntrianBpjsController::class, '_fetchData'])->name('data.antrian');
    Route::get('/apotek/detail/pasien/{id}', [AntrianBpjsController::class, 'detailPasienBpjs'])
        ->name('apotek.pasien-bpjs');
    Route::get('/apotek/proses/data/{id}', [AntrianBpjsController::class, 'obatApotek'])
        ->name('apotek.proses-pasien');
    Route::post('/apotek/proses/pasien/{id}/update/', [AntrianBpjsController::class, 'prosesPasienBpjs'])
        ->name('apotek.pasien-bpjs-update');

    // Daftar antrian umum
    Route::get('/apotek/data-umum', [AntrianUmumController::class, 'umum'])->name('data.umum');
    Route::get('/apotek/fetch/umum', [AntrianUmumController::class, '_fetchUmum'])->name('fetch-umum');
    Route::get('/apotek/pasien/umum/{id}', [AntrianUmumController::class, 'detailPasienUmum'])
        ->name('pasien-umum');
    // Route::get('/create/apotek', [AntrianBpjsController::class, 'createApotek'])->name('create.antrian');
    // Route::post('/store/apotek', [AntrianBpjsController::class, 'storeApotek'])->name('store.antrian');
    // Route::post('/apotek/{id}/update/', [AntrianBpjsController::class, 'updateApotek'])->name('update.antrian');

    // Search obat
    Route::get('/search-obat-apotek', [Select2Controller::class, 'searchObat'])->name('search.obat-apotek');
});

// // Role poli
// Route::group(['middleware' => ['auth', 'role:poli']], function () {
// });

// Role rekam medis
Route::group(['middleware' => ['auth', 'role:rekam_medis|super_admin']], function () {
    Route::get('/rekam_medis', [RekammedisController::class, 'rekam_medis'])
        ->name('rm.rekammedis');
    Route::get('/retensi', [RekammedisController::class, 'retensi'])
        ->name('rm.retensi');
    Route::get('/migrasi', [RekammedisController::class, 'migrasi_retensi'])
        ->name('rm.migrasi');
});

Route::group(['middleware' => ['auth', 'role:kasir|super_admin']], function () {

    Route::get('/kasir', [KasirController::class, 'index'])
        ->name('kasir.index');
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

Route::group(['middleware' => ['auth', 'role:pendaftaran|super_admin']], function () {

    Route::get('/data', [PendaftaranController::class, 'q']);
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
        ->name('pendaftaran.index');
    Route::get('/pendaftaran/fetch-data', [PendaftaranController::class, 'fetchData'])
        ->name('pendaftaran.fetchData');
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])
        ->name('pendaftaran.create');
    Route::get('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'createPasienTerdaftar'])
        ->name('pendaftaran.create.pasien-terdaftar');
    Route::get('/pendaftaran/dokter-poli', [PendaftaranController::class, 'getDokterPoli'])
        ->name('pendaftaran.dokter-poli');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
        ->name('pendaftaran.store');
    // Route::get('/pendaftaranmessanger', [PendaftaranController::class, 'messanger'])
    // ->name('pendaftaran.messanger');
    Route::get('/pendaftaran/create-pasien-terdaftar', [PendaftaranController::class, 'createPasienSudahPernahDaftar'])
        ->name('pendaftaran.createPasienSudahPernahDaftar');
});

Route::group(['middleware' => ['auth', 'role:radiologi|super_admin']], function () {


    Route::get('/otcradio', [RadiologiController::class, 'radiologi_otc'])
        ->name('order.radiologi-otc');
    Route::get('/umumradio', [RadiologiController::class, 'radiologi_umum'])
        ->name('order.radiologi-umum');
    Route::get('/aktifitas-user', [ActivityLogController::class, 'index'])
        ->name('aktifitas-user.index');
    Route::get('/aktifitas-user/fetch-data', [ActivityLogController::class, 'fetchData'])
        ->name('aktifitas-user.fetchData');
});


Route::group(['middleware' => ['auth', 'role:dokter|super_admin']], function () {

    Route::post('/layanan/data', [LayananController::class, 'data'])
        ->name('layanan.data');
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

require __DIR__ . '/auth.php';
