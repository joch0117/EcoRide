# EcoRide

**Projet réalisé dans le cadre du Titre Professionnel Développeur Web & Web Mobile (DWWM), Septembre 2025**  
**Auteur : Chaigneau Johan**

---

##  Présentation du projet
L’application EcoRide est une plateforme de covoiturage à vocation écologique, favorisant l’utilisation des véhicules électriques.
Chaque utilisateur peut :
Créer un compte,
Publier des trajets,
Participer à des trajets,
Ajouter des véhicules,
Commenter et noter les trajets auxquels il a participé.
Un compte administrateur permet la création de comptes employé et la suspension des utilisateurs.
Un compte employé est dédié à la modération des avis.

Le projet met l’accent sur la simplicité d’utilisation, la sécurité des données et la promotion d’une mobilité plus responsable.


## Stack technique utilisée

### Frontend

- **HTML5, CSS3, JavaScript, Bootstrap 5**
- Stack accessible, compatible tous navigateurs, facile à personnaliser pour un rendu moderne et responsive.
- Bootstrap permet un prototypage rapide et un affichage cohérent sur tous supports.
- Intégration simple et sécurisée à Symfony via Twig et Webpack Encore.

### Backend

- **PHP 8.3 avec Symfony 7**
- Symfony gère nativement la sécurité (utilisateurs, rôles, validation, CSRF) et offre une organisation claire (séparation logique métier/fonctionnelle).

### Serveur web

- **Nginx**
- Performant, simple à configurer en Docker, recommandé officiellement par Symfony pour le déploiement.
- [Voir la documentation Symfony](https://symfony.com/doc/current/setup/web_server_configuration.html)

### Conteneurisation

- **Docker et Docker Compose**
- Isolation de l’environnement, configuration personnalisée, simplification du déploiement et orchestration multi-services.

### Gestion de version

- **Git (dépôt GitHub public)**
- Branches structurées (`main`, `developpement`, `fonctionnalités`), méthode Gitflow.

### Déploiement / Production

- Déployé sur **Fly.io** (application) et **Railway** (base MariaDB).
- Architecture pensée pour :  
  - Un déploiement rapide et reproductible (Docker)  
  - Séparation claire application/base  
  - Accès sécurisé (variables d’environnement, connexions chiffrées)


---

##  Liens utiles

- **Démo en ligne (Fly.io) :** https://ecoride-white-moon-1925.fly.dev/
- **Dépôt GitHub :** https://github.com/joch0117/EcoRide.git
- **Kanban gestion de projet (Notion) :** 
- **Identifiants de test :**
    - **Admin :** admin-ecoride@ecoride.com / [Test1234!]


## Déploiement local

### Prérequis

> **Attention :** Pour un déploiement en local, veillez à vous placer sur la branche `developpement`.

Environnement nécessaire :

* **IDE** : Visual Studio Code + DBeaver
* **Docker** et **Docker Compose** installés sur votre machine
* **Node.js** et **npm** pour la gestion des assets

---

### Procédure

1. **Cloner ou télécharger la branche `developpement` du projet**

2. **Lancer les conteneurs Docker**
   Dans votre terminal, à la racine du projet :

   ```bash
   docker compose up --build
   ```

   Cette commande va construire les images :

   * `php`
   * `nginx` (serveur accessible à [http://localhost:8080])
   * `mariadb` (BDD sur port 3309)
   * `mongodb` (port 27017)

3. **Ouvrir un terminal dans le conteneur PHP**

   ```bash
   docker compose exec php sh
   ```

   Cette commande vous ouvre le terminal du conteneur.

4. **Installer les dépendances**

   * `composer install`
     *Installe toutes les dépendances PHP*
   * `npm install`
     *Installe toutes les dépendances JavaScript/Node*
   * `npm run build`
     *Compile et prépare les fichiers front-end*

---
5. **Mise en place BDD relationnelle**
- Un scripte SQL  est à votre disposition dans la branche main dans le dossier doc/sql/createDataBase.sql 
Il permet de mettre en place la base de données 
- Vous pouvez également procéder à une migration via symfony 
```bash
php bin/console doctrine:migrations:migrate
```
- créer le compte admin
```bash
php bin/console app:create-admin
```

6. **Charger les données de teste**

- un script SQL est à votre disposition dans la branche main dans le dossier doc/sql/fixture.sql
- "j'ai injecté les fixtures via script sur Dbeaver"

7. **Accéder à l'application**

- rendez-vous sur http://localhost:8080
- tester les iddentifiants admin fournient plus haut .




##  Contact

Auteur : Johan Chaigneau  
Projet réalisé pour l’ECF DWWM 2025  


---

