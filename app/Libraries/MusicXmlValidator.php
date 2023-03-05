<?php

namespace App\Libraries;

use XMLReader;

class MusicXmlValidator
{
    protected $schemaPath = __DIR__ . '/../../resources/xml/musicxml-4.0/schema/musicxml.xsd';

    public function isValidMusicXmlString(string $subject)
    {
        $xmlReader = new XMLReader();
        $xmlReader->xml($subject);
        $xmlReader->setSchema($this->schemaPath);

        while ($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLREADER::ELEMENT) {
                if (!$xmlReader->isValid()) {
                    return false;
                };
            }
        }

        $xmlReader->close();

        return true;
    }

    public function isValidMusicXmlFile(string $filepath)
    {
        $xmlReader = new XMLReader();
        $xmlReader->open($filepath);
        $xmlReader->setSchema($this->schemaPath);

        while ($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLREADER::ELEMENT) {
                if (!$xmlReader->isValid()) {
                    return false;
                }
            }
        }

        $xmlReader->close();

        return true;
    }
}
