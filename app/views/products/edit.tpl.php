<a href="<?= $router->generate('product-list') ?>" class="btn btn-success float-right">Retour</a>
    <h2>Modification un produit</h2>
    
    <h3>Liste des tags :</h3>
    <?php foreach($tags as $tag): ?>
        <span class="badge badge-primary"><?= $tag->getName() ?></span>
    <?php endforeach ?>

    <form action="" method="POST" class="mt-5">
        <div class="form-group">
            <label for="name">Nom</label>
            <input value="<?= $product->getName() ?>" name="name" type="text" class="form-control" id="name" placeholder="Nom du produit">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input  value="<?= $product->getDescription() ?>" name="description"  type="text" class="form-control" id="description" placeholder="Sous-titre" 
                aria-describedby="descriptionHelpBlock">
            <small id="subtitleHelpBlock" class="form-text text-muted">
                La description du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="picture">Image</label>
            <input value="<?= $product->getPicture() ?>" name="picture"  type="text" class="form-control" id="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
                URL relative d'une image (jpg, gif, svg ou png) fournie sur 
                <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
            </small>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input  value="<?= $product->getPrice() ?>" name="price"  type="number" class="form-control" id="price" placeholder="Prix" 
                aria-describedby="priceHelpBlock">
            <small id="priceHelpBlock" class="form-text text-muted">
                Le prix du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="rate">Note</label>
            <input value="<?= $product->getRate() ?>" name="rate"  type="number" max="5" class="form-control" id="rate" placeholder="Note" 
                aria-describedby="rateHelpBlock">
            <small id="rateHelpBlock" class="form-text text-muted">
                Le note du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="status">Statut</label>
            <select name="status"  class="custom-select" id="status" aria-describedby="statusHelpBlock">
            <option value="1" <?php if ($product->getStatus() == 1) : ?> selected<?php endif ?>>Disponible</option>
            <option value="2" <?php if ($product->getStatus() == 2) : ?> selected<?php endif ?>>Indisponible</option>
            </select>
            <small id="statusHelpBlock" class="form-text text-muted">
                Le statut du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="category">Categorie</label>
            <select name="category"  class="custom-select" id="category" aria-describedby="categoryHelpBlock">
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"
                    <?php if ($category->getId() == $product->getCategoryId()) : ?> selected<?php endif ?>
                    >
                    <?= $category->getName() ?>
                    </option>
                <?php endforeach ?>
            </select>
            <small id="categoryHelpBlock" class="form-text text-muted">
                La catégorie du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="brand">Marque</label>
            <select name="brand"  class="custom-select" id="brand" aria-describedby="brandHelpBlock">
                <?php foreach($brands as $brand): ?>
                    <option value="<?= $brand->getId() ?>"
                    <?php if ($brand->getId() == $product->getBrandId()) : ?> selected<?php endif ?>
                    >
                    <?= $brand->getName() ?>
                    </option>
                <?php endforeach ?>
            </select>
            <small id="brandHelpBlock" class="form-text text-muted">
                La marque du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type"  class="custom-select" id="type" aria-describedby="typeHelpBlock">
                <?php foreach($types as $type): ?>
                    <option
                    value="<?= $type->getId() ?>"
                    <?php if ($type->getId() == $product->getTypeId()) : ?> selected<?php endif ?>
                    >
                    <?= $type->getName() ?>
                    </option>
                <?php endforeach ?>
            </select>
            <small id="typeHelpBlock" class="form-text text-muted">
                Le type de produit 
            </small>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>