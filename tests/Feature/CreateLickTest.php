<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateLickTest extends TestCase
{
    use RefreshDatabase;

    public function test_licks_can_be_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake();
 
        $audioFile = UploadedFile::fake()->create('recording.mp3', 500);

        $response = $this->post(route('library.store'), [
            'title' => 'test lick',
            'transcription' => 'somexmltext',
            'audio' => $audioFile,
            'tempo' => '100',
            'tags' => [],
            'amp_settings' => [
                [ 'knob' => 'model', 'value' => 'test amp model' ],
                [ 'knob' => 'treble', 'value' => '6' ],
                [ 'knob' => 'bass', 'value' => '5' ],
                [ 'knob' => 'presence', 'value' => '8' ],
            ],
        ]);

        $response->assertStatus(302); // redirect on success

        $storedLicks = $user->fresh(['licks'])->licks;

        // check stored data
        $this->assertCount(1, $storedLicks);
        $this->assertEquals('test lick', $storedLicks->first()->title);

        // check uploaded file
        Storage::assertExists('audio-licks/' . $audioFile->hashName());
    }

    // TODO: test validation of transcription string and audio file format
}
