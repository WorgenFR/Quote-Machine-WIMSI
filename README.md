
<img src="https://d2zr9w65gdacs9.cloudfront.net/58120/quotem-horizontal-flat-hr1609944249logo.png" width="40%">

Author : Antoine Grappin (grap0001)<br>
Project Debut : 29/09/21


QuoteMachine est un site web permettant à n'importe qui d'accéder à une large base de données de citations provenant<br> de tous les univers (film, série, jeux-vidéos). Les citations sont regroupées par catégories afin de faciliter la recherche.


**Configuration :**

Commandes git :<br>
`git add .`
`git commit -m "[message]`
`git push`

Mise à jour des dépendances du projet :
`composer install`

Connexion à la base de données :<br>
`DATABASE_URL="mysql://root:password@127.0.0.1:3306/quote_machine?serverVersion=5.7"`

Executer une migration : <br>
`php bin/console doctrine:migrations:migrate`

Lancer le serveur de développement :<br>
`symfony server:start`

Commande de lancement de WebPack : <br>
`npm run watch`

Mise en place de la base de données : <br>
`composer db`

Exécuer les tests : <br>
`composer test`

Exécuter CS-Fixer : <br>
`composer cs`

