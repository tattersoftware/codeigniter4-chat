<?php namespace Tatter\Chat\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Tatter\Chat\Models\ConversationModel;

class Messages extends ResourceController
{
	protected $modelName = 'Tatter\Chat\Models\MessageModel';

	public function create()
	{
		// Get the conversation
		$conversation = (new ConversationModel())->find($this->request->getPost('conversation'));
		if ($conversation === null)
		{
			return;
		}
		
		// Get the current user
		if (! $userId = session(config('Chat')->userSource))
		{
			return;
		}

		// Get or create the participant
		if (! $participant = $conversation->addUser($userId))
		{
			return;
		}
		
		// Say it
		if (! $participant->say($this->request->getPost('content')))
		{
			return;
		}
		
		return $this->respondCreated(null, 'success');
	}
}
