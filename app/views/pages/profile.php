<?php
/**
 * @var \App\Entities\UserEntity $user
 * @var \App\Entities\BookEntity[] $books
 * @var string|null $error
 */
?>
<div id="main-container">
    <h1>Mon compte</h1>

    <section class="profile">

        <div class="user-infos">
            <div class="user-avatar">
                <img class="profile-picture" src="<?= img_url($user->getAvatar()); ?>" />
                <form id="avatar-form" action="<?= action_url('update-picture') ?>" method="POST" enctype="multipart/form-data">
                    <input type="file" id="avatar-input" name="avatar" accept="image/png, image/jpeg" style="display:none;">
                </form>
                <a id="modify-picture-btn" href="#" onclick="document.getElementById('avatar-input').click(); return false;">modifier</a>
            </div>

            <hr class="user-infos-separator">

            <div class="user-details">
                <span class="username"><?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?></span>
                <p><?= htmlspecialchars($user->getMemberSince(), ENT_QUOTES, 'UTF-8'); ?></p>
                <span class="library">Bibliothèque</span>
                <?php $nbBooks = count($books); ?>
                <span class="book-count">
                    <img src="<?= img_url('icons/books.svg') ?>" alt="" height="16">
                    <?= (string) $nbBooks . ' ' . ($nbBooks > 1 ? 'livres' : 'livre'); ?>
                </span>
            </div>
        </div>

        <div class="personal-infos">
            <p>Vos informations personnelles</p>

            <form action="<?= action_url('update-profile') ?>" method="POST">
                <?php if (null !== $error): ?>
                    <span class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif ?>

                <div class="input-group">
                    <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                </div>

                <div class="input-group">
                    <label for="username">Pseudo</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <button type="submit" class="btn btn--secondary">Enregistrer</button>
            </form>
        </div>

    </section>

    <section class="my-books" id="my-books">
        <table class="books-table">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Description</th>
                    <th scope="col">Disponibilité</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($books as $book): ?>
                    <tr>
                        <td>
                            <img src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">
                        </td>
                        <td><?= htmlspecialchars($book->getTitle()); ?></td>
                        <td><?= htmlspecialchars((string)$book->getAuthor()); ?></td>
                        <td class="book-description"><?= htmlspecialchars((string)$book->getDescription()); ?></td>
                        <td>
                            <span class="badge <?= ($book->getStatus()->getId() === 1) ? "available" : "not-available" ?>">
                                <?= htmlspecialchars($book->getStatus()->getName()); ?>
                            </span>
                        </td>
                        <td class="book-actions">
                            <a href="<?= action_url('livre/{id}/edition', ['id' => $book->getId()]) ?>">Éditer</a>
                            <form method="post" action="<?= action_url('livre/{id}/supprimer', ['id' => $book->getId()]) ?>" onsubmit="return confirm('Supprimer ce livre ?')">
                                <button type="submit" class="delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
<div>
