<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $router->generate('main-home') ?>">oShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?= $router->generate('main-home') ?>">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('category-list') ?>">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('product-list') ?>">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Types</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Marques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tags</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('category-homeCategoriesForm') ?>">Sélections Accueil &amp; Footer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('user-list') ?>">Utilisateurs</a>
                </li>
                <!-- Si on est connecté, on affiche le lien de déconnexion. Sinon on affiche le lien de connexion -->
                <?php
                // Dans ce projet, on a modélisé le fait qu'une personne est connectée avec la clé connectedUser dans la superglobale $_SESSION. Si cette clé est diponible, alors on considère la personne connectée.
                if(isset($_SESSION['connectedUser'])): ?>
                    <li>
                        <a class="nav-link" href="<?= $router->generate('user-logout') ?>">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a class="nav-link" href="<?= $router->generate('user-login') ?>">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>