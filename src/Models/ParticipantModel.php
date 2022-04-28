<?php

namespace Tatter\Chat\Models;

use CodeIgniter\Model;
use Tatter\Chat\Entities\Participant;

class ParticipantModel extends Model
{
    protected $table          = 'chat_participants';
    protected $primaryKey     = 'id';
    protected $returnType     = Participant::class;
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $skipValidation = false;
    protected $allowedFields  = ['conversation_id', 'user_id', 'updated_at'];
}
