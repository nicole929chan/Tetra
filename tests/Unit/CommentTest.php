<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一則comment屬於一個version()
    {
    	$version = create('App\Version');
    	$comment = create('App\Comment', ['version_id' => $version->id]);

    	$this->assertInstanceOf('App\Version', $comment->version);
    }

    /** @test */
    public function 一則comment屬於一位creator()
    {
    	$user = create('App\User');
    	$comment = create('App\Comment', ['creator_id' => $user->id]);

    	$this->assertInstanceOf('App\User', $comment->creator);
    }

    /** @test */
    public function 一則發文有多筆replies回應()
    {
        $comment = create('App\Comment');
        $replies = create('App\Reply', [
            'repliable_type' => get_class($comment),
            'repliable_id' => $comment->id,
        ], 2);

        $this->assertEquals(2, $comment->replies->count());
    }
}
