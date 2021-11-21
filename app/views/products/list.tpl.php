<a href="<?= $router->generate('product-add') ?>" class="btn btn-success float-right">Ajouter</a>
<h2>Liste des produits</h2>
<table class="table table-hover mt-4">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Nom</th>
            <th scope="col">Prix</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($productList as $currentProduct): ?>
        <tr>
            <th scope="row"><?= $currentProduct->getId() ?></th>
            <td><img class="thumbnail" src="<?= $currentProduct->getPicture() ?>"></td>
            <td><?= $currentProduct->getName() ?></td>
            <td><?= $currentProduct->getPrice() ?></td>
            <td class="text-right">
                <a href="<?= $router->generate('product-updateForm', ['id' => $currentProduct->getId()]) ?>" class="btn btn-sm btn-warning">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <!-- Example single danger button -->
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= $router->generate('product-delete', ['id' => $currentProduct->getId()]) ?>">Oui, je veux supprimer</a>
                        <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>