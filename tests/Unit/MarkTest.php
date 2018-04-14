<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一則mark屬於一個version()
    {
    	$version = create('App\Version');
    	$mark = create('App\Mark', ['version_id' => $version->id]);

    	$this->assertInstanceOf('App\Version', $mark->version);
    }

    /** @test */
    public function 一則mark屬於一位creator()
    {
    	$user = create('App\User');
    	$mark = create('App\Mark', ['creator_id' => $user->id]);

    	$this->assertInstanceOf('App\User', $mark->creator);
    }

    /** @test */
    public function 一則mark有多筆replies回應()
    {
        $mark = create('App\Mark');
        $replies = create('App\Reply', [
            'repliable_type' => get_class($mark),
            'repliable_id' => $mark->id,
        ], 2);

        $this->assertEquals(2, $mark->replies->count());
    }
}
