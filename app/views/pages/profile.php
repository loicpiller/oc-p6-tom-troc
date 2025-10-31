<div id="main-container">
    <h1>Mon compte</h1>

    <section class="profile">

        <div class="user-infos"></div>

        <div class="personal-infos">
            <p>Vos informations personnelles</p>

            <form action="" method="POST">
                <?php if (null !== $error): ?>
                    <span class="error-message"><?= $error ?></span>
                <?php endif ?>

                <div class="input-group">
                    <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $user->getEmail(); ?>" required>
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" value="<?= $user->getPassword(); ?>" required>
                </div>

                <div class="input-group">
                    <label for="username">Pseudo</label>
                    <input type="text" id="username" name="username" value="<?= $user->getUsername(); ?>" required>
                </div>

                <button type="submit" class="btn btn--secondary">Enregistrer</button>
            </form>
        </div>

    </section>

    <section class="my-books">
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
                            <img></img>
                        </td>
                        <td><?= $book->getTitle(); ?></td>
                        <td><?= $book->getAuthor(); ?></td>
                        <td class="book-description"><?= $book->getDescription(); ?></td>
                        <td>
                            <span class="badge <?= ($book->getStatus()->getId() === 1) ? "available" : "not-available" ?>">
                                <?= $book->getStatus()->getName(); ?>
                            </span>
                        </td>
                        <td class="book-actions">
                            <a href="#">Éditer</a>
                            <a class="delete" href="#">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
<div>
