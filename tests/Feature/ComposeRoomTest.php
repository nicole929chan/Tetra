<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComposeRoomTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;
    protected $room;
    protected $version;

    /** @test */
    public function 已經登入的使用者進入自己專屬的room應該要指定要看的version()
    {
        $this->initProject();

        $this->get(route('rooms.show.version', [$this->room->id, $this->version->id]))
            ->assertSee($this->room->name);
    }

    /** @test */
    public function 已經登入的使用者進入room如果沒有指定version就採用當前作業中的()
    {
        $this->initProject();

        $version = create('App\Version', [
            'room_id' => $this->room->id, 
            'image_path' => 'files/'.$this->project->id.'/versions/myVersion.png'
        ]);

        $this->get(route('rooms.show', [$this->room->id]))
            ->assertSee($this->room->name);
    }

    protected function initProject()
    {
    	$this->signIn($this->user = create('App\User'));
    	$this->project = create('App\Project');
    	\DB::table('project_user')->insert(['project_id' => $this->project->id, 'user_id' => $this->user->id]);

        $this->room = create('App\Room', ['project_id' => $this->project->id]);
    	$this->version = create('App\Version', ['room_id' => $this->room->id]);
        
        $this->room->version = $this->version->id;
        $this->room->save();
    }
}
