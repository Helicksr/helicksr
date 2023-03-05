<?php

namespace Tests\Unit;

use App\Rules\TabOrMusicXml;
use PHPUnit\Framework\TestCase;

class TabOrMusicXmlRuleTest extends TestCase
{
    public function testTextTabPasses(): void
    {
        // Using a simple flag here because PHPUnit can't properly mock Closures
        $validationPassed = true;

        $runsIfFailed = function () use (&$validationPassed) {
            $validationPassed = false;
        };

        $rule = new TabOrMusicXml();

        $validTab = 'some tab value here';

        $rule->validate('testattr', $validTab, $runsIfFailed);

        $this->assertTrue($validationPassed);
    }

    public function testValidXmlPasses(): void
    {
        // Using a simple flag here because PHPUnit can't properly mock Closures
        $validationPassed = true;

        $runsIfFailed = function () use (&$validationPassed) {
            $validationPassed = false;
        };

        $rule = new TabOrMusicXml();
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

        $rule->validate('testattr', $validXml, $runsIfFailed);

        $this->assertTrue($validationPassed);
    }

    public function testInvalidXmlFails(): void
    {
        // Using a simple flag here because PHPUnit can't properly mock Closures
        $validationPassed = true;

        $runsIfFailed = function () use (&$validationPassed) {
            $validationPassed = false;
        };

        $rule = new TabOrMusicXml();
        $invalidXml = <<<EOF
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
              <incorrect-attributes>
                <incorrect-divisions>1</incorrect-divisions>
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
              </incorrect-attributes>
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

        $rule->validate('testattr', $invalidXml, $runsIfFailed);

        $this->assertFalse($validationPassed);
    }
}
