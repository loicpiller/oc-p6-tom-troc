<div id="main-container">

    <div class="page-header">
        <h1>Nos livres à l'échange</h1>
        <form class="search-form" method="GET" action="<?= action_url('livres-echangeables'); ?>">
            <div class="search-input-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Rechercher un livre" value="<?= htmlspecialchars($search); ?>">
            </div>
        </form>
    </div>

    <div class="books-cards-container">
        <?php foreach ($books as $book): ?>
            <a href="<?= action_url('livre/{id}', ['id' => $book->getId()]); ?>" class="book-card">
                <div class="book-card-cover">
                    <img src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">
                </div>
                <div class="card-body">
                    <span class="title"><?= htmlspecialchars($book->getTitle()); ?></span>
                    <span class="author"><?= htmlspecialchars((string)$book->getAuthor()); ?></span>
                    <span class="user">Vendu par : <?= htmlspecialchars($book->getUser()->getUsername()); ?></span>
                </div>
            </a>
        <?php endforeach ?>
    </div>

</div>
