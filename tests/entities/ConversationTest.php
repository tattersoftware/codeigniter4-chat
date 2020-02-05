<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\ParticipantModel;

class ConversationTest extends \ModuleTests\Support\ModuleTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->model = new ConversationModel();
		
		// Create a mock conversation
		$conversations = new ConversationModel();

		$id = $conversations->insert($this->generateConversation());

		$this->conversation = $conversations->find($id);
	}

	public function testStartsWithoutParticipants()
	{
		$this->assertEquals([], $this->conversation->participants);
	}

	public function testAddUserCreatesParticipant()
	{
		$user = (new UserModel())->first();

		$this->conversation->addUser($user->id);

		$participants = $this->conversation->participants;

		$this->assertCount(1, $participants);
		$this->assertEquals($user->username, $participants[0]->username);
	}

	public function testSayAddsMessage()
	{
		$content = self::$faker->sentence;

		$user = (new UserModel())->first();
		$participant = $this->conversation->addUser($user->id);

		$participant->say($content);

		$messages = $this->conversation->messages;

		$this->assertEquals($content, $messages[0]->content);
	}

	public function testMessagesHaveParticipants()
	{
		$content = self::$faker->sentence;

		$user = (new UserModel())->first();
		$participant = $this->conversation->addUser($user->id);

		$participant->say($content);

		$messages = $this->conversation->messages;

		$this->assertEquals($user->username, $messages[0]->participant->username);
	}
}
