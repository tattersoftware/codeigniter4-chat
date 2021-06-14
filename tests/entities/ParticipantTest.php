<?php

use Myth\Auth\Models\UserModel;
use Myth\Auth\Test\Fakers\UserFaker;
use Tatter\Chat\Entities\Conversation;
use Tatter\Chat\Entities\Participant;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;
use Tests\Support\ModuleTestCase;

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
		$id   = model(ParticipantModel::class)->insert([
			'conversation_id' => $this->conversation->id,
			'user_id'         => 1,
		]);

		$this->participant = model(ParticipantModel::class)->find($id);
	}

	public function testGetUserReturnsDeleted()
	{
		$user = model(UserModel::class)->find(1);

		model(UserModel::class)->delete(1);

		$this->assertEquals($user->username, $this->participant->username);
	}

	public function testUsernameComesFromAccount()
	{
		$user = model(UserModel::class)->find(1);

		$this->assertEquals($user->username, $this->participant->username);
	}

	public function testGetNameFallsBackOnUsername()
	{
		$this->assertEquals('light', $this->participant->name);
	}

	public function testUsernameNoAccount()
	{
		model(UserModel::class)->skipValidation()->update(1, ['username' => null]);

		$this->assertEquals('Chatter1', $this->participant->username);
	}

	public function testUsernameNoAccountNoId()
	{
		model(UserModel::class)->skipValidation()->update(1, ['username' => null]);

		$this->participant->id = null;

		$this->assertEquals('Chatter', $this->participant->username);
	}

	public function testSayAddsMessage()
	{
		$content = 'All your base';

		$this->participant->say($content);

		$messages = model(MessageModel::class)->findAll();

		$this->assertEquals($content, $messages[0]->content);
	}
}
