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
}
