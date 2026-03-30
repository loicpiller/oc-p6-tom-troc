<?php
namespace App\Entities;

use MVC\Core\BaseEntity;

class MessageEntity extends BaseEntity
{
    protected $id;
    protected $sender_id;
    protected $receiver_id;
    protected $content;
    protected $timestamp;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSenderId()
    {
        return $this->sender_id;
    }

    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    public function setReceiverId($receiver_id)
    {
        $this->receiver_id = $receiver_id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
