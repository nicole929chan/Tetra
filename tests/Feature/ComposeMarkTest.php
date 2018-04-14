<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComposeMarkTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;
    protected $room;
    protected $version;
    protected $mark;

    /** @test */
    public function 已經登入的使用者能夠瀏覽自己專案內的marks()
    {
    	$this->initProject();

    	$response = $this->json('GET', route('marks.index', [$this->version->id]))->json();
    	$data = array_column($response, "body");
    	
    	$this->assertEquals($this->mark->body, $data[0]);
    }

    /** @test */
    public function 瀏覽的marks必須以新增的時間戳記降冪排序()
    {
    	$this->initProject();

    	$this->mark->created_at = Carbon::now()->subDay(1);
    	$this->mark->save();
    	$mark = create('App\Mark', ['version_id' => $this->version->id]);

    	$response = $this->json('GET', route('marks.index', [$this->version->id]))->json();

    	$this->assertEquals([2, 1], array_column($response, 'id'));
    }

    /** @test */
    public function 瀏覽marks一併把附屬的replies帶出來()
    {
        $this->initProject();

        $replies = create('App\Reply', [
            'repliable_type' => get_class($this->mark),
            'repliable_id' => $this->mark->id,
        ],2);

        $response = $this->json('GET', route('marks.index', [$this->version->id]))->json();

        $data = array_column($response, "replies");

        $this->assertEquals(2, count($data[0]));
    }

    /** @test */
    public function 已經登入的使用者不能夠瀏覽不是自己專案內的marks()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));
    	$response = $this->get(route('marks.index', [$this->version->id]));

    	$response->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者能夠新增自己專案內的marks()
    {
    	$this->initProject();

    	Storage::fake('public');

    	$mark = make('App\Mark', ['file_path' => $file = UploadedFile::fake()->image('mark.png')]);

    	$this->json('POST', route('marks.store', $this->version->id), $mark->toArray());

    	$this->assertDatabaseHas('marks', [
    		'version_id' => $this->version->id,
    		'creator_id' => $this->user->id,
    		'body' => $mark->body
    	]);

    	$path = "files/{$this->project->id}/" . today()->timestamp . '_mark.png';

    	Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function 新增的mark必須填寫body()
    {
    	$this->initProject();

    	$mark = make('App\Mark', ['body' => null]);

    	$this->post(route('marks.store', $this->version->id), $mark->toArray())
    	    ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 新增的mark必須填寫lat()
    {
    	$this->initProject();

    	$mark = make('App\Mark', ['lat' => null]);

    	$this->post(route('marks.store', $this->version->id), $mark->toArray())
    	    ->assertSessionHasErrors('lat');
    }

    /** @test */
    public function 新增的mark必須填寫lng()
    {
    	$this->initProject();

    	$mark = make('App\Mark', ['lng' => null]);

    	$this->post(route('marks.store', $this->version->id), $mark->toArray())
    	    ->assertSessionHasErrors('lng');
    }

    /** @test */
    public function 已經登入的使用者不能夠新增不是自己專案內的marks()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$mark = make('App\Mark');

    	$this->json('POST', route('marks.store', $this->version->id), $mark->toArray())
    	    ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者能夠更新自己發的marks()
    {
        $this->signIn($user = create('App\User'));
        
        $mark = create('App\Mark', ['creator_id' => $user->id]);

    	$this->json('patch', route('marks.update', [$mark->id]), ['body' => 'update mark']);

    	$this->assertDatabaseHas('marks', [
    		'id' => $mark->id,
    		'body' => 'update mark'
    	]);
    }

    /** @test */
    public function 更新的mark必須填寫body()
    {
    	$this->signIn($user = create('App\User'));
        
        $mark = create('App\Mark', ['creator_id' => $user->id]);

    	$this->patch(route('marks.update', [$mark->id]), ['body' => null])
    	    ->assertSessionHasErrors('body');
    }

    /** @test */
    public function 已經登入的使用者不能夠更新不是自己發的marks()
    {
    	$this->signIn($user = create('App\User'));

        $mark = create('App\Mark');

    	$this->patch(route('marks.update', [$mark->id]), ['body' => 'update mark'])
    	    ->assertStatus(403);
    }

    /** @test */
    public function 已經登入的使用者能夠刪除自己發的marks()
    {
        $this->initProject();

    	Storage::fake('public');

    	$mark = make('App\Mark', [
            'creator_id' => $this->user->id,
            'file_path' => $file = UploadedFile::fake()->image('mark.png')
        ]);

    	$this->json('POST', route('marks.store', $this->version->id), $mark->toArray());

    	$mark = \App\Mark::orderBy('id', 'desc')->first();

    	$this->delete(route('marks.destroy', [$mark->id]));

    	Storage::disk('public')->assertMissing($mark->file_path);
    	
    	$this->assertDatabaseMissing('marks', ['id' => $mark->id]);
    }

    /** @test */
    public function 已經登入的使用者不能夠刪除不是自己發的marks()
    {
    	$this->initProject();
    	auth()->logout();

    	$this->signIn($user = create('App\User'));

    	$this->delete(route('marks.destroy', [$this->mark->id]))
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

    	$this->mark = create('App\Mark', ['version_id' => $this->version->id]);
    }
}
