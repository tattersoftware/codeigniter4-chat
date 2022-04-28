<?php

namespace Tatter\Chat\Models;

use CodeIgniter\Model;
use Tatter\Chat\Entities\Message;

class MessageModel extends Model
{
    protected $table          = 'chat_messages';
    protected $primaryKey     = 'id';
    protected $returnType     = Message::class;
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $skipValidation = true;
    protected $allowedFields  = ['conversation_id', 'participant_id', 'content'];

    /**
     * Returns all unread Messages for a user.
     *
     * @param int $userId ID of the user to match
     *
     * @return Message[]
     */
    public function findUserUnread(int $userId): array
    {
        $result = $this->builder()
            ->select('chat_messages.*, chat_participants.updated_at')
            ->join(
                'chat_participants',
                'chat_messages.conversation_id = chat_participants.conversation_id AND user_id = ' . $userId
            )
            ->where('chat_messages.created_at > chat_participants.updated_at')
            ->get()->getCustomResultObject($this->returnType);

        return $result ?? [];
    }
}
