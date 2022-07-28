<?php

use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Entities\Message;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tests\Support\ModuleTestCase;

/**
 * @internal
 */
final class MessageModelTest extends ModuleTestCase
{
    private MessageModel $model;

    /**
     * A generated Conversation
     */
    private Conversation $conversation;

    /**
     * Set up the model and create a mock conversation
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model        = new MessageModel();
        $this->conversation = fake(ConversationModel::class);
    }

    public function testNoUnreadReturnsArray()
    {
        $result = $this->model->findUserUnread(1);

        $this->assertSame([], $result);
    }

    /**
     * @timeLimit 1.5
     */
    public function testUnreadReturnsMessages()
    {
        $user1 = $this->fakeUser();
        $user2 = $this->fakeUser();

        $participant1 = $this->conversation->addUser($user1->id);
        $participant2 = $this->conversation->addUser($user2->id);

        // Delay so the timestamps are different
        sleep(1);

        $participant1->say('All your base');

        $result = $this->model->findUserUnread($participant2->user_id);

        $this->assertInstanceOf(Message::class, $result[0]);
    }

    /**
     * @timeLimit 1.5
     */
    public function testUnreadReturnsIgnoresUnjoined()
    {
        $user1 = $this->fakeUser();
        $user2 = $this->fakeUser();

        $participant1 = $this->conversation->addUser($user1->id);
        $participant2 = $this->conversation->addUser($user2->id);

        // Delay so the timestamps are different
        sleep(1);

        $participant1->say('...are belong to us');

        // Create another conversation
        $conversation = fake(ConversationModel::class);

        $participant1 = $conversation->addUser($user1->id);
        $participant1->say('Somebody set us up the bomb!');

        $result = $this->model->findUserUnread($participant2->user_id);

        $this->assertCount(1, $result);
    }

    /**
     * @timeLimit 2.5
     */
    public function testUnreadReturnsCorrectCount()
    {
        $user1 = $this->fakeUser();
        $user2 = $this->fakeUser();

        $participant1 = $this->conversation->addUser($user1->id);
        $participant2 = $this->conversation->addUser($user2->id);

        $participant1->say('All your base');
        $participant2->say('...are belong to us');
        sleep(1);
        $participant1->say('Somebody set us up the bomb!');

        // Create another conversation
        $conversation = fake(ConversationModel::class);

        $participant1 = $conversation->addUser($user1->id);
        $participant2 = $conversation->addUser($user2->id);
        sleep(1);
        $participant1->say('Somebody set us up the bomb!');

        $result = $this->model->findUserUnread($participant2->user_id);

        $this->assertCount(2, $result);
    }
}
