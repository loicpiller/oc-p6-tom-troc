<?php
/**
 * @var array<int, array<string, mixed>> $userConversations
 * @var array<int, array<string, mixed>> $chatHistory
 * @var int|null $contactId
 * @var array<string, mixed>|null $activeContact
 */
?>
<div id="main-container">

    <aside>
        <h1>Messagerie</h1>
        <?php foreach ($userConversations as $conversation): ?>
            <a href="<?= action_url('messages/{contactId}', ['contactId' => $conversation['contact_id']]) ?>"
               class="contact <?= (int)$conversation['contact_id'] === $contactId ? 'contact--active' : '' ?>">
                <img class="contact__profile-picture" src="<?= img_url($conversation['avatar'] ?? 'default_profile_picture.jpg'); ?>" />
                <div>
                    <span class="contact__name"><?= htmlspecialchars($conversation['username']); ?></span>
                    <span class="contact__last-message-time"><?= htmlspecialchars($conversation['timestamp']); ?></span>
                    <span class="contact__last-message"><?= htmlspecialchars($conversation['last_message']); ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </aside>

    <?php if ($contactId !== null && $activeContact !== null): ?>
    <section class="chat">

        <div class="chat__header">
            <img src="<?= img_url($activeContact['avatar'] ?? 'default_profile_picture.jpg') ?>" />
            <span><?= htmlspecialchars($activeContact['username']) ?></span>
        </div>

        <div class="chat__messages">
        <?php foreach ($chatHistory as $message): ?>
            <?php
                $isFromContact = (int)$message['sender_id'] === $contactId;
                $dt = new \DateTime($message['timestamp']);
                $formatted = $dt->format('d.m H:i');
            ?>
            <div class="chat__message <?= $isFromContact ? 'chat__message--from-contact' : 'chat__message--from-user' ?>">
                <div class="chat__message-meta">
                    <?php if ($isFromContact): ?>
                        <img class="chat__avatar" src="<?= img_url($activeContact['avatar'] ?? 'default_profile_picture.jpg') ?>">
                    <?php endif; ?>
                    <span class="chat__timestamp"><?= $formatted ?></span>
                </div>
                <span class="content"><?= htmlspecialchars($message['content']) ?></span>
            </div>
        <?php endforeach; ?>
        </div>

        <form action="<?= action_url('send-message/{receiverId}', ['receiverId' => $contactId]); ?>" method="POST">
            <input type="text" placeholder="Tapez votre message ici" id="message" name="message" autocomplete="off">
            <input class="btn btn--primary" type="submit" value="Envoyer">
        </form>

    </section>
    <?php endif; ?>

</div>
