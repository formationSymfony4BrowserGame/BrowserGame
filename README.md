# BrowerGame : Pickomino
Jeu multijoueur local basé sur les règles du Pickomino.

## Installation  

1. Cloner le projer en local :  
    `$ git clone https://github.com/formationSymfony4BrowserGame/BrowserGame.git`  

2. Dans un terminal, monter les conteneurs Docker (MySQL, Adminer, PHP 7.4-apache) :  
    `$ cd BrowserGame`  
    `$ docker compose up`  

3. Dans un autre terminal, Dupliquer les fichiers .env-sample et .bashrc-sample en .env et .bashrc puis modifiez les selon vos préférences.
    `cp .env-sample .env`
    `cp .bashrc-sample .bashrc`

4. Dupliquer le fichier /app/.env en /app/.env.local et modifier les accès à la base de données selon les infos entrés dans .env  
    `$ cd app`  
    `$ cp .env .env.local`  

    Modifier la ligne :  
    `DATABASE_URL="mysql://dbuser:dbpass@browsergame_db_1:3306/symfony?serverVersion=5.7"`  

5. Installer les dépendances SF4.4 avec Composer :  
    `$ docker exec -it browsergame_php_1 bash`  
    `$ cd app`  
    `$ composer install`  

6. Utiliser la console Symfony afin de créer la BDD, les migrations et les fixtures.  
    `$ sf doctrine:database:create`  
    `$ sf doctrine:migrations:migrate`  
    `$ sf doctrine:fixtures:load`  
