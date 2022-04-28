<?php

use CodeIgniter\Events\Events;
use Myth\Auth\Models\UserModel;
use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Entities\Participant;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;
use Tests\Support\ModuleTestCase;

/**
 * @internal
 */
final class ParticipantTest extends ModuleTestCase
{
    /**
     * A generated Conversation
     *
     * @var Conversation
     */
    private $conversation;

    /**
     * A generated Participant
     *
     * @var Participant
     */
    private $participant;

    /**
     * Create a mock Conversation with a Participant
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->conversation = fake(ConversationModel::class);

        // Add a participant
        $id = model(ParticipantModel::class)->insert([
            'conversation_id' => $this->conversation->id,
            'user_id'         => 1,
        ]);

        $this->participant = model(ParticipantModel::class)->find($id);
    }

    public function testGetUserReturnsDeleted()
    {
        $user = model(UserModel::class)->find(1);

        model(UserModel::class)->delete(1);

        $this->assertSame($user->username, $this->participant->username);
    }

    public function testUsernameComesFromAccount()
    {
        $user = model(UserModel::class)->find(1);

        $this->assertSame($user->username, $this->participant->username);
    }

    public function testGetNameFallsBackOnUsername()
    {
        $this->assertSame('light', $this->participant->name);
    }

    public function testUsernameNoAccount()
    {
        model(UserModel::class)->skipValidation()->update(1, ['username' => null]);

        $this->assertSame('Chatter1', $this->participant->username);
    }

    public function testUsernameNoAccountNoId()
    {
        model(UserModel::class)->skipValidation()->update(1, ['username' => null]);

        $this->participant->id = null;

        $this->assertSame('Chatter', $this->participant->username);
    }

    public function testSayAddsMessage()
    {
        $content = 'All your base';

        $this->participant->say($content);

        $messages = model(MessageModel::class)->findAll();

        $this->assertSame($content, $messages[0]->content);
    }

    public function testSayTriggersEvent()
    {
        $test = null;

        Events::on('chat', static function ($data) use (&$test) {
            $test = $data['id'];
        });

        $content = 'Are belong to us';
        $result  = $this->participant->say($content);

        $this->assertSame($result, $test);
    }
}
