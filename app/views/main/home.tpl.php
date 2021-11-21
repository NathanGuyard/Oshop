<p class="display-4">
  <!-- Si la personne est connectée, on affiche un message différent du message classique -->
  <?php if(isset($_SESSION['connectedUser'])) : ?>
    <?php $currentUser = $_SESSION['connectedUser'] ?>
    Bienvenue, <?= $currentUser->getFirstname() ?> <?= $currentUser->getLastname() ?>
  <?php else: ?>
    Bienvenue dans le backOffice <strong>Dans les shoe</strong>...
  <?php endif; ?>
</p>

<div class="row mt-5">
  <div class="col-12 col-md-6">
    <div class="card text-white mb-3">
      <div class="card-header bg-primary">Liste des catégories</div>
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nom</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($categories as $currentCategory): ?>
            <tr>
              <th scope="row"><?= $currentCategory->getId() ?></th>
              <td><?= $currentCategory->getName() ?></td>
              <td class="text-right">
                <a href="" class="btn btn-sm btn-warning">
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <!-- Example single danger button -->
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Oui, je veux supprimer</a>
                    <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <a href="<?php echo $router->generate('category-list') ?>" class="btn btn-block btn-success">Voir plus</a>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6">
    <div class="card text-white mb-3">
      <div class="card-header bg-primary">Liste des produits</div>
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nom</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($products as $currentProduct): ?>
              <tr>
                <th scope="row"><?= $currentProduct->getId() ?></th>
                <td><?= $currentProduct->getName() ?></td>
                <td class="text-right">
                  <a href="" class="btn btn-sm btn-warning">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                  </a>
                  <!-- Example single danger button -->
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#">Oui, je veux supprimer</a>
                      <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach ?>
          </tbody>
        </table>
        <a href="<?php echo $router->generate('product-list') ?>" class="btn btn-block btn-success">Voir plus</a>
      </div>
    </div>
  </div>
</div>