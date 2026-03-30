<?php

namespace App\Controllers;

use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use App\Entities\UserEntity;
use DateTime;
use MVC\Core\View;

class MessagingController {
    private MessageRepository $messageRepo;
    private ?UserEntity $user;

    public function __construct()
    {
        $this->messageRepo = new MessageRepository;
    }

    public function index(?int $contactId = null): void
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if (null === $this->user) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        $userConversations = $this->messageRepo->getConversationsList($this->user->getId());
        foreach ($userConversations as &$conv) {
            $date = new DateTime($conv['timestamp']);
            $conv['timestamp'] = $date->format('Y-m-d') === date('Y-m-d')
                ? $date->format('H:i')
                : $date->format('d/m');
        }
        unset($conv);

        // If a contactId is given but no prior conversation exists, inject a virtual entry
        if ($contactId !== null) {
            $inList = array_filter($userConversations, fn($c) => (int)$c['contact_id'] === $contactId);
            if (empty($inList)) {
                $contact = (new UserRepository())->findUserById($contactId);
                if ($contact === null) {
                    throw new \Exception("User not found", 404);
                }
                array_unshift($userConversations, [
                    'contact_id'   => $contactId,
                    'username'     => $contact->getUsername(),
                    'avatar'       => $contact->getAvatar(),
                    'last_message' => '',
                    'timestamp'    => '',
                ]);
            }
        } elseif (!empty($userConversations)) {
            $contactId = (int)$userConversations[0]['contact_id'];
        }

        $chatHistory = $contactId !== null
            ? $this->messageRepo->getChatHistory($this->user->getId(), $contactId)
            : [];

        $activeContact = null;
        foreach ($userConversations as $conv) {
            if ((int)$conv['contact_id'] === $contactId) {
                $activeContact = $conv;
                break;
            }
        }

        $view = new View("Messagerie");
        $view->addStyle("messaging");
        $view->render("pages/messaging", [
            'userConversations' => $userConversations,
            'chatHistory' => $chatHistory,
            'contactId' => $contactId,
            'activeContact' => $activeContact,
        ]);
    }

    public function send(int $receiverId): void
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if (null === $this->user) {
            header('Location: ' . action_url('connexion'));
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['message'] ?? '';

            if (!empty($content) && $receiverId > 0) {
                // The timestamp is handled by the database (current_timestamp)
                $this->messageRepo->save([
                    'sender_id'   => $this->user->getId(),
                    'receiver_id' => $receiverId,
                    'content'     => $content
                ]);
            }

        }

        header('Location: ' . action_url('messages', ['contactId' => $receiverId]));
        exit();
    }
}
