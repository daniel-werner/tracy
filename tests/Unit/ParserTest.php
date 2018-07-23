<?php

namespace Tests\Unit;

use App\Utilities\WorkoutImport\Parsers\Gpx;
use App\Utilities\WorkoutImport\Parsers\ParserFactory;
use ErrorException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Tests\TestCase;
use App\Utilities\WorkoutImport\Parsers\Tcx;
use App\Utilities\WorkoutImport\Point;

class ParserTest extends TestCase
{

    public function testParseTxc()
    {
        $tcx = new Tcx();
        $path = dirname(__FILE__) . '/../sample_file.tcx';
        $data = $tcx->parse($path);


        $time = $tcx->getTime()->format('Y-m-d\TH:i:s');
        $this->assertTrue( $time === '2015-01-20T13:26:30' );
        $this->assertInstanceOf( Point::class, $data[0] );
        $this->assertTrue( count($data) === 260 );
    }

    public function testParseGpx()
    {
        $gpx = new Gpx();
        $path = dirname(__FILE__) . '/../run.gpx';
        $data = $gpx->parse($path);


        $time = $gpx->getTime()->format('Y-m-d\TH:i:s');
        $this->assertTrue( $time === '2012-10-24T23:29:40' );
        $this->assertInstanceOf( Point::class, $data[0] );
        $this->assertTrue( count($data) === 206 );
    }

    public function testParserFactory()
    {
        $tcxPath = dirname(__FILE__) . '/../sample_file.tcx';
        $gpxPath = dirname(__FILE__) . '/../run.gpx';
        $invalidFile = __FILE__;
        $nonExistingFile = 'non_existing_file';

        $parser = ParserFactory::create($tcxPath);
        $this->assertInstanceOf( Tcx::class, $parser );

        $parser = ParserFactory::create($gpxPath);
        $this->assertInstanceOf( Gpx::class, $parser );


        $this->expectException( FileNotFoundException::class );
        $parser = ParserFactory::create($nonExistingFile);

        $this->expectException( ErrorException::class );
        $parser = ParserFactory::create($invalidFile);
    }
}
