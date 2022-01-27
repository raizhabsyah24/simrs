<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriksaLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periksa_lab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaan_detail_id');
            $table->unsignedBigInteger('periksa_dokter_id')->nullable();
            $table->unsignedBigInteger('pasien_id');
            $table->date('tanggal');
            $table->string('status_diperiksa')->default('belum diperiksa');
            $table->longText('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('periksa_dokter_id')->references('id')->on('periksa_dokter')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('pemeriksaan_detail_id')->references('id')->on('pemeriksaan_detail')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('pasien_id')->references('id')->on('pasien')
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
        Schema::dropIfExists('periksa_lab');
    }
}