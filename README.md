# BrowerGame : Pickomino  
Jeu multijoueurs local basé sur les règles du Pickomino.  

## Installation  

1. Clonez le projet en local :  
    `$ git clone https://github.com/formationSymfony4BrowserGame/BrowserGame.git`  

2. Dans un terminal, dupliquez les fichiers .env-sample et .bashrc-sample en .env et .bashrc puis modifiez les selon vos préférences. Si vous êtes sous windows assurez-vous que votre éditeur de texte n'ajoute pas de retours chariot aux fins de lignes de ces fichiers. Pour cela vérifiez que votre configuration de fin de ligne soit bien `LF` et non `CRLF`.  
    `cp .env-sample .env`  
    `cp .bashrc-sample .bashrc`  

3. Montez les conteneurs Docker (MySQL, Adminer, PHP 7.4-apache) :  
    `$ cd BrowserGame`  
    `$ docker compose up`  

4. Dupliquez le fichier /app/.env en /app/.env.local et modifiez les accès à la base de données selon les infos entrés dans .env  
    `$ cd app`  
    `$ cp .env .env.local`  

    Modifiez la ligne :  
    `DATABASE_URL="mysql://dbuser:dbpass@browsergame_db_1:3306/symfony?serverVersion=5.7"`  

5. Installez les dépendances SF4.4 avec Composer :  
    `$ docker exec -it browsergame_php_1 bash`  
    `$ cd app`  
    `$ composer install`  

6. Utilisez la console Symfony afin de créer la BDD, les migrations et les fixtures.  
    `$ sf doctrine:database:create`  
    `$ sf doctrine:migrations:migrate`  
    `$ sf doctrine:fixtures:load`  
