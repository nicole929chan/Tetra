<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一位user能夠參與多個projects()
    {
    	$user = create('App\User');
    	$project = create('App\Project');

    	\DB::table('project_user')->insert(['project_id' => $project->id, 'user_id' => $user->id]);

    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->projects);
    }
}
