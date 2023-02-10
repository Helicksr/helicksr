<?php

namespace Tests\Feature;

use App\Models\Lick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateLickTest extends TestCase
{
    use RefreshDatabase;

    public function test_licks_can_be_updated()
    {
        // 1 .arrange
        // create required objects: user, lick
        $user = User::factory()->create();
        $lick = Lick::factory()->for($user)->create();
        
        // prepare edit data
        $this->actingAs($user);
        Storage::fake();
        $audioFile = UploadedFile::fake()->create('recording.mp3', 500);

        // 2. act
        // call update endpoint and handle response
        $response = $this->put(route('library.update', $lick), [
            'title' => 'new test lick title',
            'audio' => $audioFile,
        ]);

        // 3. assert
        // check response
        $response->assertStatus(302);

        // check data updated in database
        $storedLicks = $user->fresh(['licks'])->licks;
        $this->assertCount(1, $storedLicks);
        $this->assertEquals('new test lick title', $storedLicks->first()->title);

        // check uploaded file
        Storage::assertExists('audio-licks/' . $audioFile->hashName());
    }

    // TODO: test validation of transcription string and audio file format
}
