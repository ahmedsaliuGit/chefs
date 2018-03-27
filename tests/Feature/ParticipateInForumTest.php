<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_user_may_not_add_reply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/1/replies', []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // given that will have an authenticated user
        $this->signIn();
        
        // and having an existing thread
        $thread = create('App\Thread');

        // when the user add a reply to that existing thread
        $reply = make('App\Reply');
        $this->post($thread->path().'/replies', $reply->toArray());

        // their reply should be visible on the page.
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
