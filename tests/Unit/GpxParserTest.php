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
        $path = dirname(__FILE__) . '/20180427_053327.gpx';
        $data = $gpx->parse($path);

        $this->assertInstanceOf( Point::class, $data[0] );
        $this->assertTrue( count($data) === 704 );
    }
}
