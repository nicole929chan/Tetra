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

    /** @test */
    public function 一個room有多個versions()
    {
        $room = create('App\Room');
        $version = create('App\Version', ['room_id' => $room->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $room->versions);
    }

    /** @test */
    public function room能夠取得已經選擇的selection()
    {
        $room = create('App\Room');
        $selectionId = create('App\Selection', ['room_id' => $room->id])->id;

        $room->selection = $selectionId;
        $room->save();

        $this->assertInstanceOf('App\Selection', $room->selection());
    }
}
