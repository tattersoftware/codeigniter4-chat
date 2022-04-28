<?php

namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;
use RuntimeException;
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
        return model(ParticipantModel::class)
            ->where('conversation_id', $this->attributes['id'])
            ->orderBy('created_at', 'asc')
            ->findAll() ?? [];
    }

    /**
     * Gets the messages for this conversation.
     * Preloads the Participant for each message.
     *
     * @return Message[]
     */
    public function getMessages(): array
    {
        // Get the builder from the message model
        $builder = model(MessageModel::class)->builder();

        $rows = $builder
            ->select('chat_messages.*, chat_participants.user_id')
            ->join('chat_participants', 'chat_messages.participant_id = chat_participants.id', 'left')
            ->where('chat_messages.conversation_id', $this->attributes['id'])
            ->orderBy('chat_messages.created_at', 'asc')
            ->get()->getResultArray();

        if (empty($rows)) {
            return [];
        }

        // Create the Message and Participant entities from each row
        $messages = [];

        foreach ($rows as $row) {
            $participant = new Participant([
                'id'              => $row['participant_id'],
                'conversation_id' => $row['conversation_id'],
                'user_id'         => $row['user_id'],
            ]);

            unset($row['user_id']);

            $message = new Message($row);
            $message->setParticipant($participant);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Adds a user to this conversation.
     */
    public function addUser(int $userId): ?Participant
    {
        // Build the row
        $row = [
            'conversation_id' => $this->attributes['id'],
            'user_id'         => $userId,
        ];

        // Check for an existing participant
        if ($participant = model(ParticipantModel::class)->where($row)->first()) {
            // Bump the last active date and return the entity
            return $participant->active();
        }

        // Create the new participant
        if ($id = model(ParticipantModel::class)->insert($row)) {
            return model(ParticipantModel::class)->find($id);
        }

        // Something went wrong
        // @codeCoverageIgnoreStart
        $error = "Unable to add user {$userId} to conversation: " . $this->attributes['id'];
        log_message('error', $error);

        throw new RuntimeException($error);
        // @codeCoverageIgnoreEnd
    }
}
