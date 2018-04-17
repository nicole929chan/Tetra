<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一個project屬於一位creator新增()
    {
    	$creator = create('App\User', ['admin' => true]);
    	$project = create('App\Project', ['creator_id' => $creator->id]);

    	$this->assertInstanceOf('App\User', $project->creator);
    }

    /** @test */
    public function 一個project屬於多個users參與()
    {
    	$project = create('App\Project');
    	$user = create('App\User');
        
        \DB::table('project_user')->insert(['project_id' => $project->id, 'user_id' => $user->id]);

    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $project->users);
    }

    /** @test */
    public function 一個project有多個rooms()
    {
        $project = create('App\Project');
        create('App\Room', ['project_id' => $project->id], 2);

        $this->assertEquals(2, $project->rooms->count());
    }
}
