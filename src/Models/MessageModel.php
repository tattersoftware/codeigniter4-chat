<?php namespace Tatter\Chat\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
	protected $table          = 'chat_messages';
	protected $primaryKey     = 'id';
	protected $returnType     = 'Tatter\Chat\Entities\Message';
	protected $useTimestamps  = true;
	protected $useSoftDeletes = true;
	protected $skipValidation = true;
	protected $allowedFields  = ['conversation_id', 'participant_id', 'content'];

	/**
	 * Returns all unread Messages for a user.
	 *
	 * @param int $userId  ID of the user to match
	 *
	 * @return array of Messages
	 */
	public function findUserUnread(int $userId): array
	{
		$result = $this->builder()
			->select('chat_messages.*, chat_participants.updated_at')
			->join('chat_participants',
				'chat_messages.conversation_id = chat_participants.conversation_id AND user_id = ' . $userId)
			->where("chat_messages.created_at > {$this->db->DBPrefix}chat_participants.updated_at")
			->get()->getCustomResultObject($this->returnType);

		return $result ?? [];
	}
}
