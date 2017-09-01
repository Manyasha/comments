<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentControllerTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testCommentCreatedCorrectly()
    {
        $payload = [
            'message' => 'some text',
            'parentId' => null,
        ];
        $this->json('POST', 'comment/send', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'is_deleted',
                    'parent_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testCreateEmptyComment()
    {
        $payload = [
            'message' => '',
            'parentId' => null,
        ];
        $this->json('POST', 'comment/send', $payload)
            ->assertStatus(404)
            ->assertJson(['error' => 'Message is empty']);
    }

    public function testCommentUpdateCorrectly()
    {
        $payload = [
            'message' => 'some text',
            'id' => 1,
        ];
        $this->json('POST', 'comment/accept', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'is_deleted',
                    'parent_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testCommentUpdateFailed()
    {
        $payload = [
            'message' => 'some text',
            'id' => 0,
        ];
        $this->json('POST', 'comment/accept', $payload)
            ->assertStatus(404)
            ->assertJson(['error' => 'Comment not found']);
    }

    public function testCommentDeleteCorrectly()
    {
        $payload = [
            'id' => 1,
        ];
        $this->json('POST', 'comment/remove', $payload)
            ->assertStatus(200)
            ->assertJson(['success' => 'Comment mark as deleted']);
    }

    public function testGetComments()
    {
        $this->json('GET', 'comments')
            ->assertStatus(200);
    }
}
