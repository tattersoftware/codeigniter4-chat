<?php

namespace Tatter\Chat\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use RuntimeException;
use Tatter\Chat\Models\ConversationModel;
use Tatter\Chat\Models\MessageModel;

class Messages extends ResourceController
{
    protected $modelName = MessageModel::class;

    /**
     * Takes AJAX input and adds message content
     * to an existent conversation. Creates the
     * current user as a new Participant if
     * necessary.
     */
    public function create(): ?ResponseInterface
    {
        if (! $conversationId = $this->request->getPost('conversation')) {
            log_message('error', 'Conversation ID missing for Messages::create()');

            return null;
        }

        // Get the conversation
        $conversation = model(ConversationModel::class)->find($conversationId);
        if ($conversation === null) {
            log_message('error', 'Unable to locate conversation # ' . $conversationId);

            return null;
        }

        // Verify authentication
        if (! function_exists('user_id')) {
            throw new RuntimeException('Authentication system failure');
        }

        // Get the current user
        if (! $userId = user_id()) {
            log_message('error', 'Unable to determine the current user');

            return null;
        }

        // Get or create the participant
        if (! $participant = $conversation->addUser($userId)) {
            log_message('error', 'Could not add participant to conversation # ' . $conversation->id);

            return null;
        }

        // Say it
        if (! $messageId = $participant->say($this->request->getPost('content'))) {
            log_message('error', 'Failed to add content to conversation # ' . $conversation->id);

            return null;
        }

        // Respond with the pre-formatted message to display
        return $this->respondCreated(view('Tatter\Chat\Views\message', [
            'message' => $this->model->find($messageId),
        ]), 'message created');
    }
}
