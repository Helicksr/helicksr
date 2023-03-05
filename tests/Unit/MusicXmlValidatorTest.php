<?php

namespace Tests\Unit;

use App\Libraries\MusicXmlValidator;
use PHPUnit\Framework\TestCase;

class MusicXmlValidatorTest extends TestCase
{
    public function testValidString(): void
    {
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

        $validator = new MusicXmlValidator();
        $this->assertTrue($validator->isValidMusicXmlString($validXml));
    }

    public function testInvalidString(): void
    {
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
              <invalid-attributes>
                <invalid-divisions>1</invalid-divisions>
                <key>
                  <invalid-fifths>0</invalid-fifths>
                </key>
                <time>
                  <beats>4</beats>
                  <beat-type>4</beat-type>
                </time>
                <invalid-clef>
                  <sign>G</sign>
                  <line>2</line>
                </invalid-clef>
              </invalid-attributes>
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

        $validator = new MusicXmlValidator();
        $this->assertFalse($validator->isValidMusicXmlString($validXml));
    }

    public function testValidFile(): void
    {
        $validator = new MusicXmlValidator();
        $this->assertTrue($validator->isValidMusicXmlFile(__DIR__ . '/../../resources/xml/musicxml-4.0/samples/Saltarello.musicxml'));
    }

    public function testInvalidFile(): void
    {
        $validator = new MusicXmlValidator();
        $this->assertFalse($validator->isValidMusicXmlFile(__DIR__ . '/../../resources/xml/musicxml-4.0/samples/invalid.musicxml'));
    }
}
