# BrowerGame : Pickomino  
Jeu multijoueurs local basé sur les règles du Pickomino.  

## Installation  

1. Clonez le projet en local et allez dans son dossier :  
    `$ git clone https://github.com/formationSymfony4BrowserGame/BrowserGame.git && cd BrowserGame`    

2. Dans un terminal, dupliquez les fichiers .env-sample et .bashrc-sample en .env et .bashrc puis modifiez les selon vos préférences.  
    Si vous êtes sous windows assurez-vous que votre éditeur de texte n'ajoute pas de retours chariot aux fins de lignes de .bashrc.  
    Pour cela vérifiez que votre configuration de fin de ligne soit bien `LF` et non `CRLF`.  
    `$ cp .env-sample .env`  
    `$ cp .bashrc-sample .bashrc`  

3. Dupliquez le fichier /app/.env en /app/.env.local et modifiez les accès à la base de données selon les infos entrés dans .env  
    `$ cd app`  
    `$ cp .env .env.local`  

    Modifiez la ligne : `DATABASE_URL="mysql://dbuser:dbpass@browsergame_db_1:3306/symfony?serverVersion=5.7"`  
    Remplacez si différents `dbuser`, `dbpass` et `symfony` par  les valeurs des variables d'environement `DB_USER_NAME`, `DB_USER_PASSWORD` et `DB_NAME`  
    et remplacez si différent `browsergame_db_1` par le nom de votre conteneur du service db.  

4. Installez les dépendances Js et Sass :  
    `$ npm install` ou `yarn install`  

5. Compilez les assets Js et Sass : 
    `npm run dev` ou `yarn encore dev` pour les compiler une fois.  
    `npm run watch` ou `yarn encore dev --watch` pour les compiler automatiquement à chaque modification des fichiers.  

6. Montez les conteneurs Docker (MySQL, Adminer, PHP 7.4-apache) :  
    
    `$ docker compose up`  

7. Installez les dépendances SF4.4 avec Composer :  
    `$ docker exec -it browsergame_php_1 bash` (si différent, changer `browsergame_php_1` par le nom de votre conteneur du service php)  
    `$ cd app`  
    `$ composer install`  

8. Utilisez la console Symfony afin de créer la BDD, les migrations et les fixtures.  
    `$ sf doctrine:database:create`  
    `$ sf doctrine:migrations:migrate`  
    `$ sf doctrine:fixtures:load`  
