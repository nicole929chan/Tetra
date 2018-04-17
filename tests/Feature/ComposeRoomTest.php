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
    public function 已經登入的使用者不能夠進入不屬於自己的room瀏覽()
    {
        $this->initProject();
        auth()->logout();

        $this->signIn($user = create('App\User'));

        $this->get(route('rooms.show', [$this->room->id, $this->version->id]))->assertStatus(403);
    }


    /** @test */
    public function 已經登入的使用者能夠進入room瀏覽version內容()
    {
        $this->initProject();

        $this->get(route('rooms.show', [$this->room->id, $this->version->id]))
            ->assertSee($this->room->name);
    }

    /** @test */
    public function 已經登入的使用者進入room如果沒帶version就採用當前作業中的()
    {
        $this->initProject();

        $version = create('App\Version', [
            'room_id' => $this->room->id, 
            'image_path' => 'files/'.$this->project->id.'/versions/myVersion.png'
        ]);

        $this->room->version = $version->id;
        $this->room->save();

        $this->get(route('rooms.show.default', [$this->room->id]))
            ->assertSee($this->room->name);
    }

    /** @test */
    public function 已經登入的使用者進入room後不可以submi不是自己的version()
    {
        $this->initProject();
        auth()->logout();

        $this->signIn($user = create('App\User'));

        $this->post(route('rooms.store', [$this->room->id]), ['version_id' => $this->version->id])
            ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者進入room後可以submit當前version內容()
    {
        $this->initProject();

        $this->assertTrue($this->version->active);

        $this->json('POST', route('rooms.store', [$this->room->id]), ['version_id' => $this->version->id]);

        $this->assertFalse($this->version->fresh()->active);
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
