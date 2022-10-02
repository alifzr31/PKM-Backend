<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luarans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('publikasi')->unique();
            $table->string('fl_luaran');
            $table->string('artikel')->unique();
            $table->string('nip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('luarans');
    }
};
