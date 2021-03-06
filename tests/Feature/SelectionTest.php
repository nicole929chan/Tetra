<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelectionTest extends TestCase
{
    use RefreshDatabase;

    protected $project;
    protected $user;
    protected $room;
    protected $select;

    /** @test */
    public function 尚未登入的使用者不可進入selection()
    {
        $room = create('App\Room');

        $this->get(route('selection.index', [$room->id]))->assertRedirect(route('login'));
    }

    /** @test */
    public function 已經登入的使用者能夠進入自己專屬project的selection()
    {
        $this->authorization();

        $this->get(route('selection.index', [$this->room->id]))->assertSee($this->selection->image_path);
    }

    /** @test */
    public function 已經登入的使用者並不能夠瀏覽不屬於自己project的selection()
    {
        $this->signIn($user = create('App\User'));

        $project = create('App\Project');
        $room = create('App\Room', ['project_id' => $project->id]);

        $this->get(route('selection.index', [$room->id]))->assertRedirect(route('projects.index'));
    }

    /** @test */
    public function 已經登入的使用者能夠submit自己專屬project的selection()
    {
        $this->authorization();

        $this->post(route('selection.store', [$this->selection->id]));
        
        $this->assertDatabaseHas('rooms', [
            'id' => $this->room->id, 
            'selection' => $this->selection->id,
            'status' => '1'
        ]);
    }

    /** @test */
    public function 已經登入的使用者並不能夠submit不是自己專屬project的selection()
    {
        $this->signIn($user = create('App\User'));

        $project = create('App\Project');
        $room = create('App\Room', ['project_id' => $project->id]);
        
        $selection = create('App\Selection', ['room_id' => $room->id]);

        $this->post(route('selection.store', [$selection->id]))->assertStatus(403);
        
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id, 
            'selection' => 0,
            'status' => 0
        ]);
    }

    /** @test */
    public function selection已經sumit過就不能再一次提交()
    {
        $this->authorization();

        $this->room->selection = $this->selection->id;
        $this->room->status = '1';
        $this->room->save();

        $this->post(route('selection.store', [$this->selection->id]))->assertStatus(403);
    }

    protected function authorization()
    {
        $this->signIn($this->user = create('App\User'));

        $this->project = create('App\Project');
        $this->room = create('App\Room', ['project_id' => $this->project->id]);
        \DB::table('project_user')->insert(['project_id' => $this->project->id, 'user_id' => $this->user->id]);
        $this->selection = create('App\Selection', ['room_id' => $this->room->id]);
    }



}
