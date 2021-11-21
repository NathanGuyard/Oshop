<h1>Connexion</h1>

<form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Adresse Email</label>
    <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez votre courriel">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Mot de passe</label>
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Mon mot de passe complexe">
  </div>
  <input type="hidden" name="token" value="<?= $csrfToken ?>">
  <button type="submit" class="btn btn-primary">Je me connecte</button>
</form>