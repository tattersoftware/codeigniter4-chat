<?php

namespace Tatter\Chat\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\Events\Events;
use Config\Services;
use RuntimeException;
use Tatter\Chat\Models\MessageModel;
use Tatter\Chat\Models\ParticipantModel;
use Tatter\Users\UserEntity;
use Tatter\Users\UserFactory;

class Participant extends Entity
{
    protected $table = 'chat_participants';
    protected $casts = [
        'conversation_id' => 'int',
        'user_id'         => 'int',
    ];

    /**
     * Stored copy of the underlying User
     */
    private ?UserEntity $user = null;

    //--------------------------------------------------------------------
    // Getters
    //--------------------------------------------------------------------

    /**
     * Returns a display name from the underlying user account
     */
    public function getUsername(): string
    {
        if ($username = $this->getUser()->getUsername()) {
            return $username;
        }

        return isset($this->attributes['id']) ? 'Chatter' . $this->attributes['id'] : 'Chatter';
    }

    /**
     * Returns a full name from the underlying user account
     */
    public function getName(): string
    {
        return $this->getUser()->getName() ?? $this->getUsername();
    }

    /**
     * Returns initials from the underlying user account
     */
    public function getInitials(): string
    {
        $names  = explode(' ', $this->getName());
        $string = '';

        foreach ($names as $name) {
            $string .= $name[0];
        }

        return strtoupper($string);
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
     * @param string $content The content for the new message
     *
     * @return false|int|object|string ID of the new message
     */
    public function say(string $content)
    {
        $data = [
            'conversation_id' => $this->attributes['conversation_id'],
            'participant_id'  => $this->attributes['id'],
            'content'         => $content,
        ];

        if ($id = model(MessageModel::class)->insert($data)) {
            $this->active();

            $data['id'] = $id;
            Events::trigger('chat', $data);
        }

        return $id;
    }

    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Loads and returns the user account for this
     * participant using the UserProvider service
     */
    private function getUser(): UserEntity
    {
        if ($this->user !== null) {
            return $this->user;
        }

        // Load the UserFactory from the provider
        $users = Services::users();

        // If this is a Model then ignore soft delete status
        if (method_exists($users, 'withDeleted')) {
            $users->withDeleted();
        }

        // Get the User
        if (! $this->user = $users->findById($this->attributes['user_id'])) {
            $error = 'Unable to locate User ID: ' . $this->attributes['user_id'];
            log_message('error', $error);

            throw new RuntimeException($error);
        }

        return $this->user;
    }
}
