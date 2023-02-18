<?php

namespace Tests\Unit;

use App\Rules\MusicXML;
use PHPUnit\Framework\TestCase;

class MusicXMLValidationRuleTest extends TestCase
{
    public function testValidXmlPasses(): void
    {
        // Using a simple counter here because PHPUnit can't properly mock Closures
        $counter = 0;

        // counter by reference to allow updates to be seen outside closure's scope
        $fail = function () use (&$counter) {
            $counter = $counter + 1;
        };

        $rule = new MusicXML;
        $validXml = <<<EOF
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

        $rule->validate('testattr', $validXml, $fail);

        $this->assertEquals(0, $counter);
    }

    public function testInvalidXmlFails(): void
    {
        // Using a simple counter here because PHPUnit can't properly mock Closures
        $counter = 0;

        // counter by reference to allow updates to be seen outside closure's scope
        $fail = function () use (&$counter) {
            $counter = $counter + 1;
        };

        $rule = new MusicXML;
        $rule->validate('testattr', 'invalid xml here', $fail);

        $this->assertEquals(1, $counter);
    }
}
