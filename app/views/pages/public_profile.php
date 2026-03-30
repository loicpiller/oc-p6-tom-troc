<?php
/**
 * @var \App\Entities\UserEntity $user
 * @var \App\Entities\BookEntity[] $books
 */
?>
<div id="main-container">
    <section class="user-infos">
        <div class="user-avatar">
            <img class="profile-picture" src="<?= img_url($user->getAvatar()); ?>" />
        </div>

        <hr class="user-infos-separator">

        <div class="user-details">
            <span class="username"><?= htmlspecialchars($user->getUsername()); ?></span>
            <p><?= htmlspecialchars($user->getMemberSince()); ?></p>
            <span class="library">Bibliothèque</span>
            <?php $nbBooks = count($books); ?>
            <span class="book-count">
                <img src="<?= img_url('icons/books.svg') ?>" alt="" height="16">
                <?= (string) $nbBooks . ' ' . ($nbBooks > 1 ? 'livres' : 'livre'); ?>
            </span>
        </div>

        <a href="<?= action_url('messages/{contactId}', ['contactId' => $user->getId()]) ?>" class="btn btn--secondary">Écrire un message</a>
    </section>

    <section class="user-books">
        <table class="books-table">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($books as $book): ?>
                    <tr class="book-row" onclick="window.location='<?= action_url('/livre/{id}', ['id' => $book->getId()]) ?>'">
                        <td>
                            <div class="book-card-cover">
                                <img src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">
                            </div>
                        </td>
                        <td><?= htmlspecialchars($book->getTitle()); ?></td>
                        <td><?= htmlspecialchars((string)$book->getAuthor()); ?></td>
                        <td class="book-description"><?= htmlspecialchars((string)$book->getDescription()); ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
</div>
