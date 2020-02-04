<?php namespace Tatter\Chat\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
	protected $table          = 'chat_participants';
	protected $primaryKey     = 'id';
	protected $returnType     = 'Tatter\Chat\Entities\Participant';
	protected $useTimestamps  = true;
	protected $useSoftDeletes = true;
	protected $skipValidation = false;
	protected $allowedFields  = ['conversation_id', 'user_id', 'updated_at'];
}
