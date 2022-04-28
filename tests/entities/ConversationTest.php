<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Models\ConversationModel;
use Tests\Support\ModuleTestCase;

/**
 * @internal
 */
final class ConversationTest extends ModuleTestCase
{
    /**
     * A generated Conversation
     *
     * @var Conversation
     */
    private $conversation;

    /**
     * Create a mock conversation
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->conversation = fake(ConversationModel::class);
    }

    public function testStartsWithoutParticipants()
    {
        $this->assertSame([], $this->conversation->participants);
    }

    public function testStartsWithoutMessages()
    {
        $this->assertSame([], $this->conversation->messages);
    }

    public function testAddUserCreatesParticipant()
    {
        $user = model(UserModel::class)->first();
        $this->conversation->addUser($user->id);

        $participants = $this->conversation->participants;

        $this->assertCount(1, $participants);
        $this->assertSame($user->username, $participants[0]->username);
    }

    public function testSayAddsMessage()
    {
        $content = 'All your base';
        $user    = model(UserModel::class)->first();

        $participant = $this->conversation->addUser($user->id);
        $participant->say($content);

        $messages = $this->conversation->messages;

        $this->assertSame($content, $messages[0]->content);
    }

    public function testMessagesHaveParticipants()
    {
        $content = '...are belong to us';
        $user    = model(UserModel::class)->first();

        $participant = $this->conversation->addUser($user->id);
        $participant->say($content);

        $messages = $this->conversation->messages;

        $this->assertSame($user->username, $messages[0]->participant->username);
    }
}
