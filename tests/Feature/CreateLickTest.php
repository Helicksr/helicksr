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

    protected $sampleXml = <<<EOF
    <?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <!DOCTYPE score-partwise PUBLIC
    "-//Recordare//DTD MusicXML 4.0 Partwise//EN" "http://www.musicxml.org/dtds/partwise.dtd">
    <score-partwise version="4.0">
      <part-list>
        <score-part id="P1">
          <part-name>Music</part-name>
        </score-part>
      </part-list>
      <part id="P1">
        <measure number="1">
          <attributes>
            <divisions>1</divisions>
            <key>
              <fifths>0</fifths>
            </key>
            <time>
              <beats>4</beats>
              <beat-type>4</beat-type>
            </time>
            <clef>
              <sign>G</sign>
              <line>2</line>
            </clef>
          </attributes>
          <note>
            <pitch>
              <step>C</step>
              <octave>4</octave>
            </pitch>
            <duration>4</duration>
            <type>whole</type>
          </note>
        </measure>
      </part>
    </score-partwise>
    EOF;

    public function testLicksCanBeCreated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake();

        $audioFile = UploadedFile::fake()->create('recording.mp3', 500);

        $response = $this->post(route('library.store'), [
            'title' => 'test lick',
            'transcription' => $this->sampleXml,
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

    public function testLicksCannotBeCreatedWithAudioAndTranscriptionMissing()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('library.store'), [
            'title' => 'test lick',
            'transcription' => null,
            'audio' => null,
            'tempo' => '100',
            'tags' => [],
            'amp_settings' => [
                [ 'knob' => 'model', 'value' => 'test amp model' ],
                [ 'knob' => 'treble', 'value' => '6' ],
                [ 'knob' => 'bass', 'value' => '5' ],
                [ 'knob' => 'presence', 'value' => '8' ],
            ],
        ]);

        $response->assertSessionHasErrors(['transcription', 'audio']);

        // check stored data
        $storedLicks = $user->fresh(['licks'])->licks;
        $this->assertCount(0, $storedLicks);
    }

    public function testLicksCanBeCreatedWithAudioPresentAndTranscriptionMissing()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake();

        $audioFile = UploadedFile::fake()->create('recording.mp3', 500);

        $response = $this->post(route('library.store'), [
            'title' => 'test lick',
            'transcription' => null,
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

    public function testLicksCanBeCreatedWithAudioMissingAndTranscriptionPresent()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $audioFile = UploadedFile::fake()->create('recording.mp3', 500);

        $response = $this->post(route('library.store'), [
            'title' => 'test lick',
            'transcription' => $this->sampleXml,
            'audio' => null,
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
    }
}
