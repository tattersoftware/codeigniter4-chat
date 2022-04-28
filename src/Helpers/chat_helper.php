<?php

use Tatter\Chat\Models\ConversationModel;

if (! function_exists('chat')) {
    /**
     * Loads or launches a Conversation with the current user.
     *
     * @param string $uid   UID of the conversation, or empty to generate a throw-away
     * @param string $title Title to apply to the display
     *
     * @return string View of the chat UI, or failure message
     */
    function chat($uid = null, $title = null)
    {
        // Verify authentication
        if (! function_exists('user_id')) {
            throw new RuntimeException('Authentication system failure');
        }

        // Get the current user
        if (! $userId = user_id()) {
            return '<p><em>You must be logged in to chat!</em></p>';
        }

        // If no UID was passed then generate one
        if ($uid === null) {
            $uid = bin2hex(random_bytes(16));
        }

        // Check for an existing conversation
        $conversations = new ConversationModel();
        if (! $conversation = $conversations->where('uid', $uid)->first()) {
            // Create a new conversation
            $data = [
                'uid'   => $uid,
                'title' => $title ?? 'Chat',
            ];

            $id           = $conversations->insert($data);
            $conversation = $conversations->find($id);
        }

        // If a title was specified then use it over the database version
        if ($title) {
            $conversation->title = $title;
        }

        // Add/update the user
        $participant = $conversation->addUser($userId);

        return view('Tatter\Chat\Views\conversation', ['conversation' => $conversation]);
    }
}
