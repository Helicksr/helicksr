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
        Schema::create('licks', function (Blueprint $table) {
            $table->id();
            $table->text('transcription'); // musicxml format
            $table->text('title');
            $table->json('amp_settings')->nullable();
            $table->string('audio_file_path', 2048)->nullable();
            $table->integer('length')->comment('length in seconds');
            $table->json('tags');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
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
        Schema::dropIfExists('licks');
    }
};
