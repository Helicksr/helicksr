<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lick>
 */
class LickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $transcription = <<<EOF
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE score-partwise PUBLIC "-//Recordare//DTD MusicXML 4.0 Partwise//EN" "http://www.musicxml.org/dtds/partwise.dtd">
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

        $availableKnobs = ['model', 'bass', 'treble', 'presence'];

        $settings = collect($this->faker->randomElements(
          $availableKnobs,
          $this->faker->numberBetween(0, sizeof($availableKnobs) - 1),
        ))->map(fn ($knob) => [
          'knob' => $knob,
          'value' => $this->faker->numberBetween(0, 10) . '',
        ]);

        return [
            'transcription' => $transcription,
            'title' => $this->faker->sentence(3),
            'tempo' => $this->faker->numberBetween(80, 120),
            'amp_settings' => $settings,
            'audio_file_path' => $this->faker->boolean() ? 'https://helicksr-dev.s3.amazonaws.com/Free_Test_Data_500KB_MP3.mp3' : null,
            'length' => $this->faker->numberBetween(3, 20),
            'tags' => $this->faker->words($this->faker->numberBetween(0, 10)),
        ];
    }
}
