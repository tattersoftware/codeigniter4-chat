<?php

use Myth\Auth\Models\UserModel;
use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Entities\Participant;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;
use Tests\Support\ModuleTestCase;

class ParticipantTest extends ModuleTestCase
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
	public function setUp(): void
	{
		parent::setUp();

		$this->conversation = fake(ConversationModel::class);

		// Add a participant
		$user = model(UserModel::class)->first();
		$id   = model(ParticipantModel::class)->insert([
			'conversation_id' => $this->conversation->id,
			'user_id'         => $user->id,
		]);

		$this->participant = model(ParticipantModel::class)->find($id);
	}

	public function testUsernameComesFromAccount()
	{
		$user = model(UserModel::class)->first();

		$this->assertEquals($user->username, $this->participant->username);
	}

	public function testSayAddsMessage()
	{
		$content = 'All your base';

		$this->participant->say($content);

		$messages = model(MessageModel::class)->findAll();

		$this->assertEquals($content, $messages[0]->content);
	}
}
