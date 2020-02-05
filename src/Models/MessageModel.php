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
}
