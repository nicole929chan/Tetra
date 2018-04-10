<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComposeCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;
    protected $room;
    protected $version;
    protected $comment;

    /** @test */
    public function 已經登入的使用者能夠瀏覽自己專案內的comments()
    {
    	$this->initProject();

    	$response = $this->json('GET', route('comments.index', [$this->version->id]))->json();
    	
    	$this->assertEquals(1, count($response));
    }

    /** @test */
    public function 瀏覽的comments必須以新增的時間戳記降冪排序()
    {
    	$this->initProject();

    	$this->comment->created_at = Carbon::now()->subDay(1);
    	$this->comment->save();
    	$comment = create('App\Comment', ['version_id' => $this->version->id]);

    	$response = $this->json('GET', route('comments.index', [$this->version->id]))->json();

    	$this->assertEquals([2, 1], array_column($response, 'id'));
    }

    /** @test */
    public function 已經登入的使用者不能夠瀏覽不是自己專案內的comments()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));
    	$response = $this->get(route('comments.index', [$this->version->id]));

    	$response->assertStatus(403);
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



    }
}
