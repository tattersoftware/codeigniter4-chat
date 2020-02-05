<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use Tatter\Accounts\Entities\Account;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;

class Participant extends Entity
{
	protected $table = 'chat_participants';
	protected $casts = [
		'conversation_id' => 'int',
		'user_id'         => 'int',
	];

	/**
	 * Cached copy of the underlying user account
	 *
	 * @var Account|null
	 */
	 protected $account;

	//--------------------------------------------------------------------
	// Getters
	//--------------------------------------------------------------------

	/**
	 * Returns a display name from the underlying user account
	 *
	 * @return string
	 */
	 public function getUsername(): string
	 {
	 	if ($account = $this->account())
	 	{
	 		return (string) $account->username;
	 	}

		return 'Chatter #' . $this->attributes['id'];
	 }

	/**
	 * Returns a full name from the underlying user account
	 *
	 * @return string
	 */
	 public function getName(): string
	 {
	 	if ($account = $this->account())
	 	{
	 		return $account->name;
	 	}

		return '';
	 }

	//--------------------------------------------------------------------
	// Activities
	//--------------------------------------------------------------------

	/**
	 * Updates this participant's last activity date
	 *
	 * @return $this
	 */
	 public function active(): self
	 {
	 	$this->attributes['updated_at'] = date('Y-m-d H:i:s');

	 	(new ParticipantModel())->update($this->attributes['id'], ['updated_at' => $this->attributes['updated_at']]);

		return $this;
	 }

	/**
	 * Creates a new message in the conversation and updates the activity timestamp
	 *
	 * @param string $content  The content for the new message
	 *
	 * @return int|null  ID of the new message
	 */
	 public function say(string $content): ?int
	 {
	 	$data = [
			'conversation_id' => $this->attributes['conversation_id'],
			'participant_id'  => $this->attributes['id'],
			'content'         => $content,
	 	];

		if ($id = (new MessageModel())->insert($data))
		{
			$this->active();
		}

		return $id;
	 }

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Loads and returns the user account for this participant
	 *
	 * @return Account
	 */
	 protected function account(): ?Account
	 {
	 	if ($this->account)
	 	{
	 		return $this->account;
	 	}

		// Load the handler
		$handler = config('Chat')->accountHandler;
		$handler = new $handler();
		
		// Get the account
		if ($this->account = $handler->get($this->attributes['user_id']))
		{
			return $this->account;
		}

		// Something went wrong
		$error = 'Unknown user ID for chat participant: ' . $this->attributes['user_id'];
		log_message('error', $error);

		if (! config('Chat')->silent)
		{
			throw new \RuntimeException($error);
		}

		return null;
	}
}
