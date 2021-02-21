<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use Config\Services;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;
use Tatter\Users\UserEntity;
use Tatter\Users\UserFactory;
use RuntimeException;

class Participant extends Entity
{
	protected $table = 'chat_participants';
	protected $casts = [
		'conversation_id' => 'int',
		'user_id'         => 'int',
	];

	/**
	 * Cached copy of the underlying User
	 *
	 * @var UserEntity|null
	 */
	private $user;

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
		return $this->getUser()->getUsername() ?? 'Chatter #' . $this->attributes['id'];
	}

	/**
	 * Returns a full name from the underlying user account
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->getUser()->getName() ?? 'User';
	}

	/**
	 * Returns initials from the underlying user account
	 *
	 * @return string
	 */
	public function getInitials(): string
	{
		return $this->getUser()->getInitials() ?? 'XX';
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

		model(ParticipantModel::class)->update($this->attributes['id'], [
			'updated_at' => $this->attributes['updated_at'],
		]);

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

		if ($id = model(MessageModel::class)->insert($data))
		{
			$this->active();
		}

		return $id;
	}

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Loads and returns the user account for this
	 * participant using the UserProvider service
	 *
	 * @return UserEntity
	 */
	private function getUser(): UserEntity
	{
		if ($this->user)
		{
			return $this->user;
		}

		// Load the UserFactory from the provider
		$users = Services::users();

		// Get the User
		if (! $this->user = $users->findById($this->attributes['user_id']))
		{
			$error = 'Unable to locate User ID: ' . $this->attributes['user_id'];
			log_message('error', $error);
			throw new RuntimeException($error);
		}

		return $this->user;
	}
}
