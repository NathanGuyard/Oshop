<!-- Avant l'affichage  de la page, on vérifie qu'on avait pas des erreurs stockées dans la session -->
<?php
    // Si l'entrée errorsList existe
    if(isset($_SESSION['errorsList'])) {
        foreach($_SESSION['errorsList'] as $currentError): ?>
            <div class="alert alert-danger" role="alert">
                <?= $currentError ?>
            </div>
        <?php endforeach;
        // Une fois que les erreurs sont affichées, on n'en a plus besoin, on  les supprime !
        unset($_SESSION['errorsList']);
    }



    // Si l'entrée successList existe
    if(isset($_SESSION['successList'])) {
        foreach($_SESSION['successList'] as $currentSuccess): ?>
            <div class="alert alert-success" role="alert">
                <?= $currentSuccess ?>
            </div>
        <?php endforeach;
        // Une fois que les succès sont affichées, on n'en a plus besoin, on  les supprime !
        unset($_SESSION['successList']);
    }