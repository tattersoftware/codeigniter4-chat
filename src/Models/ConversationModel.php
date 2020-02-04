<?php namespace Tatter\Chat\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
	protected $table          = 'chat_conversations';
	protected $primaryKey     = 'id';
	protected $returnType     = 'Tatter\Chat\Entities\Conversation';
	protected $useTimestamps  = true;
	protected $useSoftDeletes = true;
	protected $skipValidation = false;
	protected $allowedFields  = ['name', 'icon', 'summary', 'private', 'direct'];
}
