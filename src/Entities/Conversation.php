<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use Tatter\Chat\Entities\Participant;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;

class Conversation extends Entity
{
	protected $table = 'chat_conversations';
	protected $casts = [
		'private' => 'bool',
		'direct'  => 'bool',
	];

	/**
	 * Gets the participants for this conversation
	 *
	 * @return array of Participants
	 */
	 public function getParticipants(): array
	 {
	 	return (new ParticipantModel())
	 		->where('conversation_id', $this->attributes['id'])
	 		->orderBy('created_at', 'asc')
	 		->findAll() ?? [];
	 }

	/**
	 * Gets the messages for this conversation
	 *
	 * @return array of Messages
	 */
	 public function getMessages(): array
	 {
	 	return (new MessageModel())
	 		->where('conversation_id', $this->attributes['id'])
	 		->orderBy('created_at', 'asc')
	 		->findAll() ?? [];
	 }

	/**
	 * Adds a user to this conversation.
	 *
	 * @return Participant|null
	 */
	 public function addUser(int $userId): ?Participant
	 {
		$participants = new ParticipantModel();

		// Build the row
	 	$row = [
	 		'conversation_id' => $this->attributes['id'],
	 		'user_id'         => $userId,
	 	];

		// Check for an existing participant
		if ($participant = $participants->where($row)->first())
		{
			// Bump the last active date and return the entity
			return $participant->active();
		}

		// Create the new participant
		if ($id = $participants->insert($row))
		{
			return $participants->find($id);
		}

		// Something went wrong
		$error = "Unable to add user {$userId} to conversation: " . $this->attributes['id'];
		log_message('error', $error);

		if (! config('Chat')->silent)
		{
			throw new \RuntimeException($error);
		}

		return null;
	 }
}
