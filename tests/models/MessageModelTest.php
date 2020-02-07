<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;

class MessageModelTest extends \ModuleTests\Support\ModuleTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->model = new MessageModel();
		
		// Create a mock conversation
		$conversations = new ConversationModel();

		$id = $conversations->insert($this->generateConversation());

		$this->conversation = $conversations->find($id);
	}

	public function testNoUnreadReturnsArray()
	{
		$result = $this->model->findUserUnread(1);

		$this->assertEquals([], $result);
	}

	public function testUnreadReturnsMessages()
	{
		$users   = (new UserModel())->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		// Delay so the timestamps are different
		sleep(1);

		$participant1->say(self::$faker->sentence);

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertInstanceOf('Tatter\Chat\Entities\Message', $result[0]);
	}

	public function testUnreadReturnsIgnoresUnjoined()
	{
		$users   = (new UserModel())->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		// Delay so the timestamps are different
		sleep(1);

		$participant1->say(self::$faker->sentence);

		// Create another conversation
		$conversations = new ConversationModel();

		$id = $conversations->insert($this->generateConversation());

		$conversation = $conversations->find($id);

		$participant1 = $conversation->addUser($users[0]->id);
		$participant1->say(self::$faker->sentence);

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertCount(1, $result);
	}

	public function testUnreadReturnsCorrectCount()
	{
		$users = (new UserModel())->findAll();

		$participant1 = $this->conversation->addUser($users[0]->id);
		$participant2 = $this->conversation->addUser($users[1]->id);

		$participant1->say(self::$faker->sentence);
		$participant2->say(self::$faker->sentence);
		sleep(1);
		$participant1->say(self::$faker->sentence);

		// Create another conversation
		$conversations = new ConversationModel();

		$id = $conversations->insert($this->generateConversation());

		$conversation = $conversations->find($id);

		$participant1 = $conversation->addUser($users[0]->id);
		$participant2 = $conversation->addUser($users[1]->id);
		sleep(1);
		$participant1->say(self::$faker->sentence);

		$result = $this->model->findUserUnread($participant2->user_id);

		$this->assertCount(2, $result);
	}

}
