<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Entities\Message;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tests\Support\ModuleTestCase;

class MessageModelTest extends ModuleTestCase
{
	/**
	 * @var MessageModel
	 */
	private $model;

	/**
	 * A generated Conversation
	 *
	 * @var Conversation
	 */
	private $conversation;

	/**
	 * Set up the model and create a mock conversation
	 */
	public function setUp(): void
	{
		parent::setUp();

		$this->model        = new MessageModel();
		$this->conversation = fake(ConversationModel::class);
	}

	public function testNoUnreadReturnsArray()
	{
		$result = $this->model->findUserUnread(1);

		$this->assertEquals([], $result);
	}

	public function testUnreadReturnsMessages()
	{
		$users = model(UserModel::class)->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		// Delay so the timestamps are different
		sleep(1);

		$participant1->say('All your base');

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertInstanceOf(Message::class, $result[0]);
	}

	public function testUnreadReturnsIgnoresUnjoined()
	{
		$users = model(UserModel::class)->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		// Delay so the timestamps are different
		sleep(1);

		$participant1->say('...are belong to us');

		// Create another conversation
		$conversation = fake(ConversationModel::class);

		$participant1 = $conversation->addUser($users[0]->id);
		$participant1->say('Somebody set us up the bomb!');

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertCount(1, $result);
	}

	public function testUnreadReturnsCorrectCount()
	{
		$users = (new UserModel())->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		$participant1->say('All your base');
		$participant2->say('...are belong to us');
		sleep(1);
		$participant1->say('Somebody set us up the bomb!');

		// Create another conversation
		$conversation = fake(ConversationModel::class);

		$participant1 = $conversation->addUser($users[0]->id);
		$participant2 = $conversation->addUser($users[1]->id);
		sleep(1);
		$participant1->say('Somebody set us up the bomb!');

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertCount(2, $result);
	}
}
