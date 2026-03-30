<?php
/**
 * @var \App\Entities\BookStatusEntity[] $statuses
 * @var string|null $error
 * @var array<string, mixed> $formData
 */
?>

<div id="main-container">

    <a id="back-link" href="<?= action_url('mon-compte') ?>#my-books">← retour</a>

    <h1>Ajouter un livre</h1>

    <section id="book-infos">
        <div id="img-container">
            <span>Photo</span>
            <img src="<?= img_url("default_book_picture.jpg"); ?>">
            <a id="modify-picture-link" href="#" onclick="document.getElementById('image-input').click(); return false;">Ajouter une photo</a>
            <?php if ($error !== null) : ?>
                <p class="upload-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>

        <form action="<?= action_url('livre/nouveau') ?>" method="post" enctype="multipart/form-data">
            <input type="file" id="image-input" name="image" accept="image/jpeg,image/png" style="display:none;">

            <div class="input-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars((string) $formData['title'], ENT_QUOTES, 'UTF-8') ?>" required />
            </div>
            <div class="input-group">
                <label for="author">Auteur</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars((string) $formData['author'], ENT_QUOTES, 'UTF-8') ?>" required />
            </div>
            <div class="input-group">
                <label for="description">Commentaire</label>
                <textarea id="description" name="description"><?= htmlspecialchars((string) $formData['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="input-group">
                <label for="status">Disponibilité</label>
                <select id="status" name="status_id" required>
                    <?php foreach ($statuses as $status) : ?>
                        <option value="<?= $status->getId() ?>" <?= $status->getId() === (int) $formData['status_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status->getName(), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn--primary" type="submit">Ajouter le livre</button>
        </form>
    </section>

</div>
