# Projet CSE de saint vincent par Allan Rondeau, Quioc Ewan, Enzo N'GA 

Application pour la gestion du CSE du lycée saint vincent. Cette application permet de gérer les différentes offres du CSE.
Le CSE dispose d'une partie partenaire qui lui permet de présenter ses différents partenaires.
L'application permet au CSE de Saint Vincent de gérer ses données depuis une page d'administration disponible depuis le backoffice ce qui 
rend le CSE autonome sur la gestion de ses données.

## Installation

Assurez vous que composer est installé sinon suivez ce lien :

[Chapitre d'installation](https://getcomposer.org/doc/00-intro.md)

Assurez vous que node JS est installé sinon suivez ce lien :

[Chapitre d'installation](https://nodejs.org/fr/download/package-manager)

Assurez vous que Symfony est installé sinon suivez ce lien :

[Chapitre d'installation](https://symfony.com/download)

Assurez vous que docker est installé sinon suivez ce lien : 

[Chapitre d'installation](https://docs.docker.com/engine/install/)


### Etape 1 : Installation de l'application

Ouvrir une console et taper la commande :

```console
composer install
```

Cette commande permet d'installer les dépendances PHP du projet

### Etape 2 : Installation des dépendances JS

Ouvrir une console et taper la commande : 

```console
npm install
```

Cette commande permet d'installer les dépendances PHP du projet

### Création de la Bdd

Avant toute chose il faut créer un fichier .env.local

Dans ce fichier il est nécessaire d'ajouter la ligne:

DATABASE_URL="mysql://nomUtilisateurMysql:MotDePasseMysql@127.0.0.1:3306/nomDuProjet?serverVersion=8&charset=utf8mb4"

Ensuite ouvrir une console et taper la commande : 

```console
php bin/console d:d:c
```
Cette commande permet de créer la base de donnée

Une fois les 2 étapes d'avant réalisées il faut générer les tables.
Pour cela ouvrez une console et tapez : 

```console
php bin/console d:s:u -f
```

### Lancement de l'application

Pour lancer l'application il faut taper la commande :

```console
symfony server:start -d
npm run watch
docker compose up -d
```
docker est nécessaire à l'utilisation du mail catcher pour tester l'envoie de mail.

L'application est maintenant prête.
