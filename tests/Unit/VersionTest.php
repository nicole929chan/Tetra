<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一個version屬於一個room()
    {
    	$room = create('App\Room');
    	$version = create('App\Version', ['room_id' => $room->id]);

    	$this->assertInstanceOf('App\Room', $version->room);
    }

    /** @test */
    public function 一個version有多筆comments()
    {
    	$version = create('App\Version');
    	$comment = create('App\Comment', ['version_id' => $version->id]);
        
    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $version->comments);
    }

    /** @test */
    public function 一個version有多筆marks()
    {
        $version = create('App\Version');
        $mark = create('App\Mark', ['version_id' => $version->id]);

        $this->assertEquals(1, $version->marks->count());
    }
}
