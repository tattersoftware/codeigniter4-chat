<?php namespace Tatter\Chat\Entities;

use CodeIgniter\Entity;

class Message extends Entity
{
	protected $table = 'chat_messages';
	protected $casts = [
		'conversation_id' => 'int',
		'participant_id'  => 'int',
	];
}
