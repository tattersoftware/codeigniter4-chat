<?php

namespace Tatter\Chat\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatTables extends Migration
{
    public function up()
    {
        // Conversations
        $fields = [
            'title'      => ['type' => 'varchar', 'constraint' => 255],
            'uid'        => ['type' => 'varchar', 'constraint' => 255],
            'private'    => ['type' => 'boolean', 'default' => 0],
            'direct'     => ['type' => 'boolean', 'default' => 0],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ];

        $this->forge->addField('id');
        $this->forge->addField($fields);

        $this->forge->addUniqueKey('uid');
        $this->forge->addKey(['deleted_at', 'id']);
        $this->forge->addKey('created_at');

        $this->forge->createTable('chat_conversations');

        // Participants
        $fields = [
            'conversation_id' => ['type' => 'int', 'unsigned' => true],
            'user_id'         => ['type' => 'int', 'unsigned' => true],
            'created_at'      => ['type' => 'datetime', 'null' => true],
            'updated_at'      => ['type' => 'datetime', 'null' => true],
            'deleted_at'      => ['type' => 'datetime', 'null' => true],
        ];

        $this->forge->addField('id');
        $this->forge->addField($fields);

        $this->forge->addKey(['conversation_id', 'user_id']);
        $this->forge->addKey(['user_id', 'conversation_id']);
        $this->forge->addKey('updated_at');

        $this->forge->createTable('chat_participants');

        // Messages
        $fields = [
            'conversation_id' => ['type' => 'int', 'unsigned' => true],
            'participant_id'  => ['type' => 'int', 'unsigned' => true],
            'content'         => ['type' => 'text'],
            'created_at'      => ['type' => 'datetime', 'null' => true],
            'updated_at'      => ['type' => 'datetime', 'null' => true],
            'deleted_at'      => ['type' => 'datetime', 'null' => true],
        ];

        $this->forge->addField('id');
        $this->forge->addField($fields);

        $this->forge->addKey(['conversation_id', 'created_at']);
        $this->forge->addKey(['created_at', 'conversation_id']);

        $this->forge->createTable('chat_messages');
    }

    public function down()
    {
        $this->forge->dropTable('chat_conversations');
        $this->forge->dropTable('chat_participants');
        $this->forge->dropTable('chat_messages');
    }
}
