<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use League\CommonMark\GithubFlavoredMarkdownConverter;
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
	 * Initial default values
	 */
	protected $attributes = [
		'content' => '',
	];

	/**
	 * Stored copy of the sending Participant.
	 *
	 * @var Participant|null
	 */
	private $participant;

	/**
	 * Returns the message content with optional formatting.
	 *
	 * @return string
	 */
	public function getContent($format = 'html'): string
	{
		switch ($format)
		{
			case 'html':
				return nl2br(strip_tags($this->attributes['content']));

			case 'json':
				return json_encode($this->attributes['content']);

			case 'markdown':
				return (new GithubFlavoredMarkdownConverter([
					'html_input'         => 'strip',
					'allow_unsafe_links' => false,
				]))->convertToHtml($this->attributes['content']);

			case 'raw':
			default:
				return $this->attributes['content'];
		}
	}
	
	/**
	 * Injects the Participant. Used to eager load
	 * batches of Messages.
	 *
	 * @param Participant|null $participant
	 *
	 * @return void
	 */
	public function setParticipant(Participant $participant = null): void
	{
		$this->participant = $participant;
	}
	
	/**
	 * Loads and returns the participant who sent this message.
	 * Ideally this is already injected by the Conversation.
	 *
	 * @return Participant
	 */
	protected function getParticipant(): ?Participant
	{
		if (is_null($this->participant))
		{
			$this->participant = model(ParticipantModel::class)->find($this->attributes['participant_id']);
		}

		return $this->participant;
	}
}
