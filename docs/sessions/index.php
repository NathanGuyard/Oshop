<?php

// les sessions sont des petits espaces de stockage sur le serveur. A chaque espace correspond une clé, stockée dans un cookie.
// Pour utiliser le système  de sessions, toutes nos pages doivent exécuter session_start().
session_start();

// Grace à session_start, quand on charge la page, PHP fait une vérification pour nous : es-ce qu'on a une clé pour accéder à une session. Si oui, alors on peut utiliser le tableau $_SESSION et récupérer les infos dedans. Si non, PHP nous crée une nouvelle session et nous donne la clé sous la forme d'un cookie appelé PHPSESSID

// $_SESSION est une super globale représentant la session, c'est un tableau et on peut lire et stocker des infos dedans. Ces infos sont perdues à partir du moment où on perd la clé.
var_dump($_SESSION);

// $_SESSION['loggedIn'] = true;
// var_dump($_SESSION);

if(isset($_SESSION['loggedIn'])) {
    echo "Je suis connecté";
}