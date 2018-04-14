<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadActivityTest extends TestCase
{
	use RefreshDatabase;

	protected $user;
    protected $project;
    protected $room;
    protected $version;
    protected $comment;
    protected $mark;
    protected $reply;

    /** @test */
    public function 已經登入的使用者能夠一次讀取自己專案內的marks與comments()
    {
    	$this->initProject();

    	$response = $this->json('GET', route('activities.index', [$this->version->id]))->json();

    	$this->assertEquals(2, count($response));
    }

    /** @test */
    public function 讀取的marks與comments須依照新增的時間戳記降冪排序()
    {
    	$this->initProject();

    	$mark2 = create('App\Mark', ['version_id' => $this->version->id]);
    	$mark2->created_at = Carbon::now()->addDays(1);
    	$mark2->save();

    	$this->comment->created_at = Carbon::now()->addDays(2);
    	$this->comment->save();

    	$this->mark->created_at = Carbon::now()->addDays(3);
    	$this->mark->save();

    	$response = $this->json('GET', route('activities.index', [$this->version->id]))->json();

    	$this->assertEquals(
    		[$mark2->creator_id, $this->comment->creator_id, $this->mark->creator_id], 
    		array_column($response, "creator_id")
    	);
    }

    /** @test */
    public function 已經登入的使用者不能夠一次讀取不是自己專案內的marks與comments()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$this->get(route('activities.index', [$this->version->id]))
    	    ->assertStatus(403);
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

    	$this->comment = create('App\Comment', ['version_id' => $this->version->id]);
    	$this->mark = create('App\Mark', ['version_id' => $this->version->id]);
    	$this->reply = create('App\Reply', ['repliable_type' => get_class($this->mark), 'repliable_id' => $this->mark->id]);
    }
}
