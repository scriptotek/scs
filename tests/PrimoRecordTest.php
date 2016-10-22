<?php

use App\PrimoRecord;
use BCLib\PrimoServices\DeepLink;
use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class PrimoRecordTest extends TestCase
{
	public function loadPrimoRecord($filename)
	{
		$xml = file_get_contents(__DIR__ . '/data/' . $filename);

        $root = new QuiteSimpleXMLElement($xml);
        $root->registerXPathNamespace('s', 'http://www.exlibrisgroup.com/xsd/jaguar/search');
        $root->registerXPathNamespace('p', 'http://www.exlibrisgroup.com/xsd/primo/primo_nm_bib');

        $doc = $root->first('//s:DOC');
        $dl = new DeepLink('','');
        return PrimoRecord::make($doc, $dl, false, [])->toArray(true);
	}

    public function testKeywords()
    {
    	$record = $this->loadPrimoRecord('PrimoRecordTest1.xml');

        $this->assertSetsEqual(
            ['engelsk', 'toefl', 'språktester', 'testen', 'språkbruk', 'øvingsbøker'],
            array_get($record, 'subjects.keyword')
        );

        $this->assertSetsEqual(
            [],
            array_get($record, 'subjects.subject')
        );

    }

    public function testGeographicNames()
    {
        $record = $this->loadPrimoRecord('PrimoRecordGeo.xml');

        $this->assertSetsEqual(
            ['Nordpolen', 'Arktis'],
            array_get($record, 'subjects.place')
        );

    }
}