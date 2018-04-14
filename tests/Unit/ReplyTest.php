<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一則reply由一位user回應()
    {
    	$user = create('App\User');
    	$reply = create('App\Reply', ['owner_id' => $user->id]);

    	$this->assertEquals($user->id, $reply->owner->id);
    }

    /** @test */
    public function 一則reply能夠歸屬於一則comment()
    {
    	$comment = create('App\Comment');
    	$reply = create('App\Reply', [
    		'repliable_type' => get_class($comment),
    		'repliable_id' => $comment->id,
    	]);

    	$this->assertInstanceOf(get_class($comment), $reply->repliable);
    }
}
