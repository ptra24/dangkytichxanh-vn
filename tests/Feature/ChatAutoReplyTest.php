<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ChatMessage;

class ChatAutoReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_message_triggers_auto_reply()
    {
        $payload = [
            'session_id' => 'test-session-123',
            'customer_name' => 'Alice',
            'message' => 'Hello support!'
        ];

        $response = $this->postJson('/api/chat/messages', $payload);

        $response->assertStatus(201);

        // Verify customer message was stored
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'test-session-123',
            'sender_type' => 'customer',
            'message' => 'Hello support!'
        ]);

        // Verify auto-reply was triggered
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'test-session-123',
            'sender_type' => 'employee',
            'sender_name' => 'Hệ thống tự động'
        ]);

        // Total messages in session should be 2 (1 customer + 1 auto reply)
        $this->assertEquals(2, ChatMessage::where('session_id', 'test-session-123')->count());
    }

    public function test_subsequent_message_does_not_trigger_another_auto_reply()
    {
        // First message
        $this->postJson('/api/chat/messages', [
            'session_id' => 'test-session-123',
            'customer_name' => 'Alice',
            'message' => 'Hello support!'
        ]);

        // Second message
        $this->postJson('/api/chat/messages', [
            'session_id' => 'test-session-123',
            'customer_name' => 'Alice',
            'message' => 'Are you there?'
        ]);

        // Total messages in session should be 3 (2 customer + 1 auto reply)
        $this->assertEquals(3, ChatMessage::where('session_id', 'test-session-123')->count());
        $this->assertEquals(1, ChatMessage::where('session_id', 'test-session-123')
            ->where('sender_name', 'Hệ thống tự động')
            ->count());
    }

    public function test_no_auto_reply_if_human_already_replied()
    {
        // Create manual entry of human reply in session first
        ChatMessage::create([
            'session_id' => 'test-session-123',
            'customer_name' => 'Alice',
            'sender_type' => 'employee',
            'sender_name' => 'Staff Khoa',
            'message' => 'Hi, how can I help you today?'
        ]);

        // Customer sends message
        $this->postJson('/api/chat/messages', [
            'session_id' => 'test-session-123',
            'customer_name' => 'Alice',
            'message' => 'I need help with blue badge!'
        ]);

        // Total messages in session should be 2 (1 human + 1 customer), no auto replies!
        $this->assertEquals(2, ChatMessage::where('session_id', 'test-session-123')->count());
        $this->assertEquals(0, ChatMessage::where('session_id', 'test-session-123')
            ->where('sender_name', 'Hệ thống tự động')
            ->count());
    }
}
