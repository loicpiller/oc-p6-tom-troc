<?php
/**
 * @var \App\Entities\BookEntity $book
 * @var \App\Entities\BookStatusEntity[] $statuses
 * @var string|null $error
 */
?>

<div id="main-container">

    <a id="back-link" href="<?= action_url('mon-compte') ?>#my-books">← retour</a>

    <h1>Modifier les informations</h1>

    <section id="book-infos">
        <div id="img-container">
            <span>Photo</span>
            <img src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">
            <a id="modify-picture-link" href="#" onclick="document.getElementById('image-input').click(); return false;">Modifier la photo</a>
            <?php if ($error !== null) : ?>
                <p class="upload-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </div>

        <form action="<?= action_url('livre/{id}/edition', ['id' => $book->getId()]) ?>" method="post" enctype="multipart/form-data">
            <input type="file" id="image-input" name="image" accept="image/jpeg,image/png" style="display:none;">

            <div class="input-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book->getTitle()) ?>" required />
            </div>
            <div class="input-group">
                <label for="author">Auteur</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars((string)$book->getAuthor()) ?>" required />
            </div>
            <div class="input-group">
                <label for="description">Commentaire</label>
                <textarea id="description" name="description"><?= htmlspecialchars((string)$book->getDescription()) ?></textarea>
            </div>
            <div class="input-group">
                <label for="status">Disponibilité</label>
                <select id="status" name="status_id" required>
                    <?php foreach ($statuses as $status) : ?>
                        <option value="<?= $status->getId() ?>" <?= $status->getId() === $book->getStatusId() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn--primary" type="submit">Valider</button>
        </form>
    </section>

</div>
