<a href="<?= $router->generate('user-list') ?>" class="btn btn-success float-right">Retour</a>
        <h2>Ajouter un utilisateur</h2>
        
        <form action="" method="POST" class="mt-5">

            <?php $email = (isset($_SESSION['inputValues']['email']))? $_SESSION['inputValues']['email'] : '' ; ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" value="<?= $email ?>" name="email" class="form-control" id="email" placeholder="Email de l'utilisateur">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password"  name="password" class="form-control" id="password" placeholder="Mot de passe de l'utilisateur">
            </div>

            <?php $lastname = (isset($_SESSION['inputValues']['lastname']))? $_SESSION['inputValues']['lastname'] : '' ; ?>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" value="<?= $lastname ?>" name="lastname" class="form-control" id="lastname" placeholder="Nom">
            </div>
            <?php $firstname = (isset($_SESSION['inputValues']['firstname']))? $_SESSION['inputValues']['firstname'] : '' ; ?>
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text"  value="<?= $firstname ?>" name="firstname" class="form-control" id="firstname" placeholder="Prénom">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="custom-select"  name="role" id="role">
                    <option value="catalog-manager">Catalog Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="status">Statut</label>
                <select class="custom-select"  name="status" id="status">
                    <option value="1">Activé</option>
                    <option value="2">Désactivé</option>
                </select>
            </div>
            <input name="token" type="hidden" value="<?= $csrfToken ?>">
        
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>