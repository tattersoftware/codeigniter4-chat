<?php namespace Tatter\Chat\Models;

use CodeIgniter\Model;
use Faker\Generator;
use Tatter\Chat\Entities\Conversation;

class ConversationModel extends Model
{
	protected $table          = 'chat_conversations';
	protected $primaryKey     = 'id';
	protected $returnType     = Conversation::class;
	protected $useTimestamps  = true;
	protected $useSoftDeletes = true;
	protected $skipValidation = false;

	protected $allowedFields   = ['title', 'uid', 'private', 'direct'];
	protected $validationRules = ['uid' => 'required'];

	/**
	 * Faked data for Fabricator.
	 *
	 * @param Generator $faker
	 *
	 * @return Conversation
	 */
	public function fake(Generator &$faker): Conversation
	{
		return new Conversation([
			'title' => $faker->company,
			'uid'   => implode('_', $faker->words),
		]);
	}
}
