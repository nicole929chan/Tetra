<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComposeReplyTest extends TestCase
{
	use RefreshDatabase;

    protected $user;
    protected $project;
    protected $room;
    protected $version;
    protected $comment;

    /** @test */
    public function 未登入的使用者不能夠做出reply()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->post(route('replies.store', $this->comment->id), ['body' => 'new reply'])
    	    ->assertRedirect(route('login'));
    }

    /** @test */
    public function 已經登入的使用者能夠依據一則comment作reply()
    {
    	$this->initProject();

    	$this->post(route('replies.store', $this->comment->id), ['body' => 'new reply']);

    	$this->assertDatabaseHas('replies', ['repliable_id' => $this->comment->id, 'body' => 'new reply']);
    }

    /** @test */
    public function reply能夠包含上傳檔案()
    {
    	Storage::fake('public');

    	$this->initProject();

    	$this->post(route('replies.store', $this->comment->id), [
    		'body' => 'new reply',
    		'file_path' => $file = UploadedFile::fake()->image('reply.png')
    	]);

    	$this->assertDatabaseHas('replies', ['repliable_id' => $this->comment->id, 'body' => 'new reply']);

    	$path = "files/{$this->project->id}/" . \Carbon\Carbon::now()->timestamp . '_reply.png';
    	
    	Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function 新增的reply必須填寫body()
    {
    	$this->initProject();

    	$this->post(route('replies.store', $this->comment->id), ['body' => null])
    	    ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 已經登入的使用者不能夠對不屬於自己project做出reply()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$this->post(route('replies.store', $this->comment->id), ['body' => 'new reply'])
    	    ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者夠對修改自己發的reply()
    {
        $this->initProject();

        $this->post(route('replies.store', $this->comment->id), ['body' => 'new reply']);

        $reply = $this->comment->replies->first();

        $this->patch(route('replies.update', [$reply->id]), ['body' => 'update reply']);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'update reply']);
    }

    /** @test */
    public function 修改reply的body必填()
    {
        $this->initProject();

        $this->post(route('replies.store', $this->comment->id), ['body' => 'new reply']);

        $reply = $this->comment->replies->first();

        $this->patch(route('replies.update', [$reply->id]), ['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 已經登入的使用者不能夠修改不是自己發的reply()
    {
        $this->initProject();
        $this->post(route('replies.store', $this->comment->id), ['body' => 'new reply']);
        $reply = $this->comment->replies->first();

        auth()->logout();
        $this->signIn($user = create('App\User'));

        $this->patch(route('replies.update', [$reply->id]), ['body' => 'update reply'])
            ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者夠對刪除自己發的reply包含檔案()
    {
        Storage::fake('public');

        $this->initProject();
        $this->post(route('replies.store', $this->comment->id), [
            'body' => 'new reply',
            'file_path' => $file = UploadedFile::fake()->image('reply.png')
        ]);
        $path = "files/{$this->project->id}/" . \Carbon\Carbon::now()->timestamp . '_reply.png';
        
        Storage::disk('public')->assertExists($path);

        $reply = $this->comment->replies->first();

        $this->delete(route('replies.destroy', [$reply->id]));

        Storage::disk('public')->assertMissing($reply->file_path);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function 已經登入的使用者不夠對刪除不是自己發的reply()
    {
        $this->initProject();
        $this->post(route('replies.store', $this->comment->id), ['body' => 'new reply']);
        $reply = $this->comment->replies->first();

        auth()->logout();
        $this->signIn($user = create('App\User'));

        $this->delete(route('replies.destroy', [$reply->id]))->assertStatus(403);
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
