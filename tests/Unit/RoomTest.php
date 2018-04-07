<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一個room隸屬於一個project()
    {
    	$project = create('App\Project');
    	$room = create('App\Room', ['project_id' => $project->id]);

    	$this->assertInstanceOf('App\Project', $room->project);
    }

    /** @test */
    public function 一個room有多個selections()
    {
    	$room = create('App\Room');
    	$selection = create('App\Selection', ['room_id' => $room->id]);

    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $room->selections);
    }
}
