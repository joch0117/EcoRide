# **EcoRide**

**Application de covoiturage écologique — Projet réalisé dans le cadre du Titre Professionnel DWWM (2025)**
**Auteur : Johan Chaigneau**

---

## **Présentation**

**EcoRide** est une application web de **covoiturage éco-responsable**, conçue pour encourager l’utilisation de véhicules électriques et hybrides.
Elle met l’accent sur :

* la **simplicité d’utilisation** pour les usagers,
* la **sécurité des données** (rôles, validation, CSRF, etc.),
* et la **promotion d’une mobilité durable**.

L’utilisateur peut :

* Créer un compte sécurisé
* Publier et gérer ses trajets
* Réserver ou participer à un covoiturage
* Ajouter ses véhicules
* Donner une **note et un avis** sur ses trajets

Deux espaces spécifiques complètent l’application :

* **Espace Employé** : validation et modération des avis
* **Espace Administrateur** : gestion des utilisateurs, statistiques, création d’employés, suspension de comptes

---


## **Stack technique**

### **Frontend**

* **HTML5 / CSS3 / JavaScript / Bootstrap 5**
* Interface responsive et moderne, intégrée via **Twig**
* **Webpack Encore** pour la compilation des assets

### **Backend**

* **PHP 8.3 + Symfony 7**
* Architecture MVC complète
* Gestion des utilisateurs, rôles et sécurité via le composant Security
* Formulaires, validations et logique métier structurée

### **Bases de données**

* **MariaDB** : gestion relationnelle (utilisateurs, trajets, réservations)
* **MongoDB** : gestion non relationnelle (avis, statistiques, snapshots)

### **Serveur et Conteneurisation**

* **Nginx** comme reverse proxy et serveur web
* **PHP-FPM** dans un conteneur dédié
* **Docker Compose** pour orchestrer :

  * `php` (backend)
  * `nginx` (serveur web)
  * `mariadb` (BDD principale)
  * `mongodb` (données secondaires)
* Configuration d’un **réseau Docker privé**  pour sécuriser les échanges entre services

---

## **Déploiement**

### **Infrastructure**

Le projet a été **déployé en production** sur un **serveur VPS OVH** à l’aide de Docker, avec une architecture modulaire et sécurisée :

* Réseau privé Docker isolant les conteneurs applicatifs
* Service Nginx exposé sur le port 80/443
* MariaDB et MongoDB accessibles uniquement en interne
* Certificats SSL gérés par le reverse proxy
* Variables d’environnement stockées via secrets Docker

### **Environnement de production**

* Application : conteneur Docker (Nginx + PHP-FPM)
* Base de données : MariaDB et MongoDB hébergées sur OVH


### **Démo en ligne**

https://www.ecoride-eco.fr/

---

## **Installation locale**

### **Prérequis**

* Docker & Docker Compose
* Node.js & npm
* Composer
* Un IDE (VS Code recommandé)
* DBeaver (ou tout client SQL)

### **Procédure**

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/joch0117/EcoRide.git
   cd EcoRide
   ```

2. **Lancer les conteneurs**

   ```bash
   docker compose up -d --build
   ```

   Les services suivants seront disponibles :

   * PHP : port 9000
   * Nginx : [http://localhost:8080](http://localhost:8080)
   * MariaDB : port 3309
   * MongoDB : port 27017

3. **Installer les dépendances**

   ```bash
   docker compose exec php sh
   composer install
   npm install
   npm run build
   ```

4. **Initialiser la base de données**

   ```bash
   php bin/console doctrine:migrations:migrate
   php bin/console app:create-admin
   ```

5. **Données de test**
   Un script SQL est disponible dans `doc/sql/fixture.sql`

6. **Accès à l’application**
   [http://localhost:8080](http://localhost:8080)

   * **Admin** : `admin-ecoride@ecoride.com` / `Azote355538!`

---

## **Compétences mises en œuvre**

* Développement complet **front + back**
* Création et intégration d’une **architecture Docker multi-conteneurs**
* Configuration et optimisation **Nginx + PHP-FPM**
* Gestion des **bases MariaDB et MongoDB**
* Sécurisation des formulaires et des routes
* Déploiement sur **VPS OVH** avec réseau Docker privé
* Documentation technique complète et procédure de déploiement
* Maîtrise du workflow **Git / GitHub / Docker**

---
## Documentation:

* **Documentation compléte disponible dans l'onglet doc ainsi que les fixtures de l'application**

## **Auteur**

**Johan Chaigneau**
Développeur Web & Web Mobile — Reconversion professionnelle
Projet réalisé dans le cadre du Titre Professionnel DWWM (session 2025)
[https://github.com/joch0117]
