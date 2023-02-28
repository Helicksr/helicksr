<?php

namespace Tests\Feature;

use App\Models\Lick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateLickTest extends TestCase
{
    use RefreshDatabase;

    public function testLicksCanBeUpdated()
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
            'tempo' => 120,
        ]);

        // 3. assert
        // check response
        $response->assertStatus(302);

        // check data updated in database
        $storedLicks = $user->fresh(['licks'])->licks;
        $this->assertCount(1, $storedLicks);
        $this->assertEquals('new test lick title', $storedLicks->first()->title);
        $this->assertEquals(120, $storedLicks->first()->tempo);

        // check uploaded file
        Storage::assertExists('audio-licks/' . $audioFile->hashName());
    }

    #[DataProvider('updateCasesData')]
    public function testValidationAudioAndTranscription(
        array $originalData,
        array $updateData,
        array $fieldsWithErrors = [],
    ) {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake();

        // 1. arrange
        // lick has audio and transcription
        $lick = Lick::factory()->for($user)->create($originalData);

        // 2. act
        // call update endpoint and handle response
        // $response = $this->followingRedirects()->put(route('library.update', $lick), $updateData);
        $response = $this->put(route('library.update', $lick), $updateData);

        // 3. assert
        // check response
        $response->assertStatus(302);

        // check data updated in database
        $storedLicks = $user->fresh(['licks'])->licks;
        $this->assertCount(1, $storedLicks);

        if (!empty($fieldsWithErrors)) {
            // check response has validation error
            $response->assertSessionHasErrors($fieldsWithErrors);
            return;
        }

        if (array_key_exists('transcription', $updateData)) {
            $this->assertEquals($updateData['transcription'], $storedLicks->first()->transcription);
        } else {
            $this->assertEquals($originalData['transcription'], $storedLicks->first()->transcription);
        }

        if (array_key_exists('audio', $updateData)) {
            if ($updateData['audio'] === null) { // when removing, check file is gone and path set to null
                $this->assertNull($storedLicks->first()->audio_file_path);
                Storage::assertMissing($originalData['audio_file_path']);
            }

            if ($updateData['audio'] instanceof File) { // when updating check file was uploaded and path updated
                $filepath = 'audio-licks/' . $updateData['audio']->hashName();
                $this->assertEquals($storedLicks->first()->audio_file_path, $filepath);
                Storage::assertExists($filepath);
            }
        } else {
            // when not updating check path was same as initial value
            $this->assertEquals($storedLicks->first()->audio_file_path, $originalData['audio_file_path']);
        }
    }

    public function updateCasesData(): array
    {
        $originalXml = <<<EOF
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

        $updatedXml = <<<EOF
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
                  <step>D</step>
                  <octave>4</octave>
                </pitch>
                <duration>4</duration>
                <type>whole</type>
              </note>
            </measure>
          </part>
        </score-partwise>
        EOF;

        $storedHasBothAudioAndTranscription = [
            'audio_file_path' => 'audio-licks/Free_Test_Data_500KB_MP3.mp3',
            'transcription' => $originalXml,
        ];

        $storedHasAudioAndNoTranscription = [
            'audio_file_path' => 'audio-licks/Free_Test_Data_500KB_MP3.mp3',
            'transcription' => null,
        ];

        $storedHasTranscriptionAndNoAudio = [
            'audio_file_path' => null,
            'transcription' => $originalXml,
        ];

        $updatesBothAudioAndTranscription = [
            'audio' => UploadedFile::fake()->create('recording.mp3', 500),
            'transcription' => $updatedXml,
        ];

        $updatesAudioAndRemovesTranscription = [
            'audio' => UploadedFile::fake()->create('recording.mp3', 500),
            'transcription' => null,
        ];

        $updatesAudioAndLeavesTranscriptionAlone = [
            'audio' => UploadedFile::fake()->create('recording.mp3', 500),
            // 'transcription' => null,
        ];

        $removesAudioAndUpdatesTranscription = [
            'audio' => null,
            'transcription' => $updatedXml,
        ];

        $leavesAudioAloneAndUpdatesTranscription = [
            // 'audio' => UploadedFile::fake()->create('recording.mp3', 500),
            'transcription' => $updatedXml,
        ];

        $removesBothAudioAndTranscription = [
            'audio' => null,
            'transcription' => null,
        ];

        $removesAudioAndLeavesTranscriptionAlone = [
            'audio' => null,
            // 'transcription' => $updatedXml,
        ];

        $leavesAudioAloneAndRemovesTranscription = [
            // 'audio' => UploadedFile::fake()->create('recording.mp3', 500),
            'transcription' => null,
        ];

        return [
            // 1. original lick has both audio and transcription
            // 1.1 updating both audio and transcription => accept
            'storedHasBothAudio_&Transc;UpdateBothAudio_&Transc' => [
                $storedHasBothAudioAndTranscription,
                $updatesBothAudioAndTranscription,
            ],
            // 1.2 updating audio with new file while removing transcription at the same time => accept
            'storedHasBothAudio_&Transc;UpdateAudio_& removeTransc' => [
                $storedHasBothAudioAndTranscription,
                $updatesAudioAndRemovesTranscription,
            ],
            // 1.3 updating audio with new file while leaving the existing transcription alone => accept
            'storedHasBothAudio_&Transc;UpdateAudio_& preserveTransc' => [
                $storedHasBothAudioAndTranscription,
                $updatesAudioAndLeavesTranscriptionAlone,
            ],
            // 1.4 removing audio while updating transcription with new data at the same time => accept
            'storedHasBothAudio_&Transc;RemoveAudio_& updateTransc' => [
                $storedHasBothAudioAndTranscription,
                $removesAudioAndUpdatesTranscription,
            ],
            // 1.5 updating transcription with new data while leaving the existing audio alone => accept
            'storedHasBothAudio_&Transc;PreserveAudio_& updateTransc' => [
                $storedHasBothAudioAndTranscription,
                $leavesAudioAloneAndUpdatesTranscription,
            ],
            // 1.6 removing audio and transcription at the same time => reject
            'storedHasBothAudio_&Transc;RemoveAudio_& removeTransc' => [
                $storedHasBothAudioAndTranscription,
                $removesBothAudioAndTranscription,
                ['audio', 'transcription'],
            ],
            // 1.7 removing audio while leaving the existing transcription alone => accept
            'storedHasBothAudio_&Transc;RemoveAudio_& preserveTransc' => [
                $storedHasBothAudioAndTranscription,
                $removesAudioAndLeavesTranscriptionAlone,
            ],
            // 1.8 removing transcription while leaving the existing audio alone => accept
            'storedHasBothAudio_&Transc;PreserveAudio_& removeTransc' => [
                $storedHasBothAudioAndTranscription,
                $leavesAudioAloneAndRemovesTranscription,
            ],

            // 2. lick has audio and doesn't have transcription
            // 2.1 updating both audio and transcription => accept
            'storedHasAudio_&NoTransc;UpdateBothAudio_&Transc' => [
                $storedHasAudioAndNoTranscription,
                $updatesBothAudioAndTranscription,
            ],
            // 2.2 updating audio with new file while removing transcription at the same time => accept
            'storedHasAudio_&NoTransc;UpdateAudio_& removeTransc' => [
                $storedHasAudioAndNoTranscription,
                $updatesAudioAndRemovesTranscription,
            ],
            // 2.3 updating audio with new file while leaving the existing transcription alone => accept
            'storedHasAudio_&NoTransc;UpdateAudio_& preserveTransc' => [
                $storedHasAudioAndNoTranscription,
                $updatesAudioAndLeavesTranscriptionAlone,
            ],
            // 2.4 removing audio while updating transcription with new data at the same time => accept
            'storedHasAudio_&NoTransc;RemoveAudio_& updateTransc' => [
                $storedHasAudioAndNoTranscription,
                $removesAudioAndUpdatesTranscription,
            ],
            // 2.5 updating transcription with new data while leaving the existing audio alone => accept
            'storedHasAudio_&NoTransc;PreserveAudio_& updateTransc' => [
                $storedHasAudioAndNoTranscription,
                $leavesAudioAloneAndUpdatesTranscription,
            ],
            // 2.6 removing audio and transcription at the same time => reject
            'storedHasAudio_&NoTransc;RemoveAudio_& removeTransc' => [
                $storedHasAudioAndNoTranscription,
                $removesBothAudioAndTranscription,
                ['audio', 'transcription'],
            ],
            // 2.7 removing audio while leaving the existing transcription alone => reject
            'storedHasAudio_&NoTransc;RemoveAudio_& preserveTransc' => [
                $storedHasAudioAndNoTranscription,
                $removesAudioAndLeavesTranscriptionAlone,
                ['audio', 'transcription'],
            ],
            // 2.8 removing transcription while leaving the existing audio alone => accept
            'storedHasAudio_&NoTransc;PreserveAudio_& removeTransc' => [
                $storedHasAudioAndNoTranscription,
                $leavesAudioAloneAndRemovesTranscription,
            ],


            // 3. lick doesn't have audio and has transcription
            // 3.1 updating both audio and transcription => accept
            'storedHasTransc_&NoAudio;UpdateBothAudio_&Transc' => [
                $storedHasTranscriptionAndNoAudio,
                $updatesBothAudioAndTranscription,
            ],
            // 3.2 updating audio with new file while removing transcription at the same time => accept
            'storedHasTransc_&NoAudio;UpdateAudio_& removeTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $updatesAudioAndRemovesTranscription,
            ],
            // 3.3 updating audio with new file while leaving the existing transcription alone => accept
            'storedHasTransc_&NoAudio;UpdateAudio_& preserveTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $updatesAudioAndLeavesTranscriptionAlone,
            ],
            // 3.4 removing audio while updating transcription with new data at the same time => accept
            'storedHasTransc_&NoAudio;RemoveAudio_& updateTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $removesAudioAndUpdatesTranscription,
            ],
            // 3.5 updating transcription with new data while leaving the existing audio alone => accept
            'storedHasTransc_&NoAudio;PreserveAudio_& updateTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $leavesAudioAloneAndUpdatesTranscription,
            ],
            // 3.6 removing audio and transcription at the same time => reject
            'storedHasTransc_&NoAudio;RemoveAudio_& removeTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $removesBothAudioAndTranscription,
                ['audio', 'transcription'],
            ],
            // 3.7 removing audio while leaving the existing transcription alone => accept
            'storedHasTransc_&NoAudio;RemoveAudio_& preserveTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $removesAudioAndLeavesTranscriptionAlone,
            ],
            // 3.8 removing transcription while leaving the existing audio alone => reject
            'storedHasTransc_&NoAudio;PreserveAudio_& removeTransc' => [
                $storedHasTranscriptionAndNoAudio,
                $leavesAudioAloneAndRemovesTranscription,
                ['audio', 'transcription'],
            ],
        ];
    }
}
