<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utilities\WorkoutImport\Parsers\Gpx;
use App\Utilities\WorkoutImport\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GpxParserTest extends TestCase
{

    public function testRoles()
    {
        $gpx = new Gpx();
        $path = dirname(__FILE__) . '/../run.gpx';
        $data = $gpx->parse($path);


        $time = $gpx->getTime()->format('Y-m-d\TH:i:s');
        $this->assertTrue( $time === '2012-10-24T23:29:40' );
        $this->assertInstanceOf( Point::class, $data[0] );
        $this->assertTrue( count($data) === 206 );
    }
}
