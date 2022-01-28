<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('no_sep')->unique()->nullable();
            $table->string('no_bpjs')->nullable();
            $table->string('no_rekam_medis')->nullable();
            $table->unsignedBigInteger('faskes_id')->nullable();
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('kategori_pasien');
            $table->date('tanggal');
            $table->bigInteger('total_tagihan_layanan')->default(0);
            $table->bigInteger('total_tagihan_obat')->default(0);
            $table->string('status')->default('belum selesai');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('faskes_id')->references('id')->on('faskes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('pasien_id')->references('id')->on('pasien')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('kategori_pasien')->references('id')->on('kategori_pasien')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaan');
    }
}
