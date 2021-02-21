<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use Tatter\Chat\Entities\Participant;
use Tatter\Chat\Models\ParticipantModel;

class Message extends Entity
{
	protected $table = 'chat_messages';
	protected $casts = [
		'conversation_id' => 'int',
		'participant_id'  => 'int',
	];

	/**
	 * Returns the message content with optional formatting.
	 *
	 * @return string
	 */
	protected function getContent($format = 'html'): string
	{
		switch ($format)
		{
			case 'html':
				return nl2br(strip_tags($this->attributes['content']));
			break;

			case 'json':
				return json_encode($this->attributes['content']);
			break;

			case 'raw':
			default:
				return $this->attributes['content'];
			break;
		}
	}
	
	/**
	 * Loads and returns the participant who sent this message.
	 * Ideally this is already injected by the Conversation.
	 *
	 * @return Account
	 */
	protected function getParticipant(): ?Participant
	{
		if (empty($this->attributes['participant']))
		{
			$this->attributes['participant'] = model(ParticipantModel::class)->find($this->attributes['participant_id']);
		}

		return $this->attributes['participant'];
	}
}
