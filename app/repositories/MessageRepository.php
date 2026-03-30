<?php

namespace App\Repositories;

use MVC\Core\BaseRepository;

class MessageRepository extends BaseRepository
{
    protected string $table = 'message';

    /**
     * Get unique contacts for the sidebar with their last message.
     * @param int $userId
     * @return array
     */
    public function getConversationsList(int $userId): array
    {
        $sql = "SELECT 
                    u.id as contact_id, 
                    u.username, 
                    u.avatar, 
                    m.content as last_message, 
                    m.timestamp
                FROM message m
                INNER JOIN user u ON u.id = CASE 
                    WHEN m.sender_id = :userId THEN m.receiver_id 
                    ELSE m.sender_id 
                END
                WHERE (m.sender_id = :userId OR m.receiver_id = :userId)
                AND m.id IN (
                    SELECT MAX(id) 
                    FROM message 
                    WHERE sender_id = :userId OR receiver_id = :userId
                    GROUP BY CASE 
                        WHEN sender_id = :userId THEN receiver_id 
                        ELSE sender_id 
                    END
                )
                ORDER BY m.timestamp DESC";

        return $this->db->customQuery($sql, ['userId' => $userId]);
    }

    /**
     * Get all messages between two users for the main chat area.
     * @param int $userId
     * @param int $contactId
     * @return array
     */
    public function getChatHistory(int $userId, int $contactId): array
    {
        $sent = $this->db
            ->select('sender_id', 'timestamp', 'content')
            ->where('sender_id', '=', $userId)
            ->where('receiver_id', '=', $contactId)
            ->get();

        $received = $this->db
            ->select('sender_id', 'timestamp', 'content')
            ->where('sender_id', '=', $contactId)
            ->where('receiver_id', '=', $userId)
            ->get();

        $all = array_merge($sent, $received);
        
        usort($all, fn($a, $b) => strcmp($a['timestamp'], $b['timestamp']));
        
        return $all;
    }
}
