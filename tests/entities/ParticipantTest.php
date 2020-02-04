<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;

class ParticipantTest extends \ModuleTests\Support\ModuleTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		// Create a mock conversation
		$conversations = new ConversationModel();

		$id = $conversations->insert($this->generateConversation());

		$this->conversation = $conversations->find($id);

		// Add a participant
		$this->model = new ParticipantModel();

		$user = (new UserModel())->first();
		$id   = $this->model->insert([
			'conversation_id' => $this->conversation->id,
			'user_id'         => $user->id,
		]);

		$this->participant = $this->model->find($id);
	}

	public function testUsernameComesFromAccount()
	{
		$user = (new UserModel())->first();

		$this->assertEquals($user->username, $this->participant->username);
	}

	public function testSayAddsMessage()
	{
		$content = 'hello world';

		$this->participant->say($content);

		$messages = (new MessageModel())->findAll();

		$this->assertEquals($content, $messages[0]->content);
	}
}
