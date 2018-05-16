<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    public function 瀏覽comments一併把附屬的replies帶出來()
    {
        $this->initProject();

        $replies = create('App\Reply', [
            'repliable_type' => get_class($this->comment),
            'repliable_id' => $this->comment->id,
        ],2);

        $response = $this->json('GET', route('comments.index', [$this->version->id]))->json();

        $data = array_column($response, "replies");

        $this->assertEquals(2, count($data[0]));
    }

    /** @test */
    public function 瀏覽comments一併把它的creator帶出來()
    {
        $this->initProject();

        $response = $this->json('GET', route('comments.index', [$this->version->id]))->json();

        $data = array_column($response, "creator");

        $this->assertEquals($this->comment->creator->name, $data[0]['name']);
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

    /** @test */
    public function 已經登入的使用者能夠新增自己專案內的comments()
    {
    	$this->initProject();

    	Storage::fake('public');

    	$comment = make('App\Comment', ['file_path' => $file = UploadedFile::fake()->image('comment.png')]);

    	$this->json('POST', route('comments.store', $this->version->id), $comment->toArray());

    	$this->assertDatabaseHas('comments', [
    		'version_id' => $this->version->id,
    		'creator_id' => $this->user->id,
    		'body' => $comment->body
    	]);

    	$path = "files/{$this->project->id}/" . \Carbon\Carbon::now()->timestamp . '_comment.png';

    	Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function 新增的comment必須填寫body()
    {
    	$this->initProject();

    	$comment = make('App\Comment', ['body' => null]);

    	$this->post(route('comments.store', $this->version->id), $comment->toArray())
    	    ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 已經登入的使用者不能夠新增不是自己專案內的comments()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$comment = make('App\Comment');

    	$this->json('POST', route('comments.store', $this->version->id), $comment->toArray())
    	    ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者能夠更新自己發的comments()
    {
        $this->signIn($user = create('App\User'));
        
        $comment = create('App\Comment', ['creator_id' => $user->id]);

    	$this->json('patch', route('comments.update', [$comment->id]), ['body' => 'update comment']);

    	$this->assertDatabaseHas('comments', [
    		'id' => $comment->id,
    		'body' => 'update comment'
    	]);
    }

    /** @test */
    public function 更新的comment必須填寫body()
    {
    	$this->signIn($user = create('App\User'));
        
        $comment = create('App\Comment', ['creator_id' => $user->id]);

    	$this->patch(route('comments.update', [$comment->id]), ['body' => null])
    	    ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 已經登入的使用者不能夠更新不是自己發的comments()
    {
    	$this->signIn($user = create('App\User'));

        $comment = create('App\Comment');

    	$this->patch(route('comments.update', [$comment->id]), ['body' => 'update comment'])
    	    ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者能夠刪除自己發的comments()
    {
        $this->initProject();

    	Storage::fake('public');

    	$comment = make('App\Comment', [
            'creator_id' => $this->user->id,
            'file_path' => $file = UploadedFile::fake()->image('comment.png')
        ]);

    	$this->json('POST', route('comments.store', $this->version->id), $comment->toArray());

    	$comment = \App\Comment::orderBy('id', 'desc')->first();

    	$this->delete(route('comments.destroy', [$comment->id]));

    	Storage::disk('public')->assertMissing($comment->file_path);
    	
    	$this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function 已經登入的使用者不能夠刪除不是自己發的comments()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$this->delete(route('comments.destroy', [$this->comment->id]))
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

    }
}
