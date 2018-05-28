<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一個selection屬於一個room()
    {
    	$room = create('App\Room');
    	$selection = create('App\Selection',['room_id' => $room->id]);

    	$this->assertInstanceOf('App\Room', $selection->room);
    }
}
