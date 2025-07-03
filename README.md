# easy-ENT

Plateforme numérique éducative pour la gestion scolaire, la communication et le suivi pédagogique.

## Présentation

**easy-ENT** est une plateforme numérique éducative conçue pour moderniser la gestion scolaire, améliorer la communication entre enseignants, élèves et parents, et faciliter l'accès aux cours, devoirs et bulletins de notes. Elle s'adapte à tous les niveaux (primaire, secondaire) et fonctionne sur mobile, tablette ou ordinateur.

### Ce qui distingue easy-ENT
- **Accessibilité mobile et offline** : Fonctionne même sans internet en mode local.
- **Pensé pour les réalités africaines** : Simple à prendre en main, adapté aux structures francophones, supports multilingues.
- **Sécurité & confidentialité** : Données protégées, hébergées localement ou dans le cloud.
- **Installation rapide & support local** : Déploiement en moins de 48h, accompagnement technique et formation.

## Fonctionnalités principales

- Gestion des utilisateurs (élèves, enseignants, parents, administrateurs)
- Gestion des cours, matières, emplois du temps
- Suivi des notes et résultats
- Messagerie interne et formulaire de contact
- Gestion des absences
- Paiement et facturation des frais scolaires (intégration Stripe)
- Notifications par email (inscription, nouveaux cours, factures)
- Espace personnel pour chaque utilisateur (profil, avatar, cours suivis)
- Espace enseignant (gestion des élèves, notes, documents)
- Espace parent (suivi des enfants, réception des factures)
- Tableau de bord administrateur (gestion des utilisateurs, matières, horaires, messages)

## Installation

### Prérequis
- PHP >= 8.2
- Composer
- Docker & Docker Compose (recommandé)

### Démarrage rapide avec Docker

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/makombelajo/easy-ENT.git
   cd easy-ENT
   ```
2. Lancez les conteneurs :
   ```bash
   docker-compose up --build
   ```
3. Accédez à l'application :
   - [http://localhost:8080](http://localhost:8080) (application)
   - [http://localhost:8081](http://localhost:8081) (phpMyAdmin)
   - [http://localhost:8025](http://localhost:8025) (Mailhog)

### Variables d'environnement

Créez un fichier `.env` à la racine de `app/` avec au minimum :

```
APP_ENV=dev
APP_SECRET=ChangeMe
DATABASE_URL=mysql://admin:admin7791@database:3306/easy_ENT
MAILER_DSN=smtp://mailhog:1025
MESSENGER_TRANSPORT_DSN=doctrine://default
```

Adaptez selon votre environnement (voir `app/config/packages/*.yaml`).

### Installation manuelle (hors Docker)

1. Installez les dépendances PHP :
   ```bash
   cd app
   composer install
   composer require stripe/stripe-php
   ```
2. Configurez votre base de données (MySQL ou PostgreSQL supporté).
3. Lancez les migrations :
   ```bash
   cd ..
   docker compose exec php /bin/bash
   symmfony console make:migration
   ```
4. Vérifier le port :
   ```bash
   http://127.0.0.1:8080
   ```

## Structure du projet

- `app/src/Controller/` : Contrôleurs principaux (Main, Admin, Parent, Teacher...)
- `app/src/Entity/` : Entités Doctrine (Users, Courses, Classes, Results...)
- `app/templates/` : Vues Twig (pages principales, emails, profils...)
- `app/public/` : Fichiers publics (index.php, assets, uploads...)
- `app/config/` : Configuration Symfony (routes, services, packages...)
- `app/tests/` : Tests automatisés

## Tests

Lancez les tests PHPUnit :
```bash
cd app
php bin/phpunit
```

## Contribution

1. Forkez le projet
2. Créez une branche (`git checkout -b feature/ma-fonctionnalite`)
3. Commitez vos modifications
4. Poussez la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvrez une Pull Request

## Licence

Projet propriétaire. Contactez l'auteur pour toute utilisation commerciale ou déploiement à grande échelle.

---

Pour toute question, ouvrez une issue ou contactez l'équipe via le formulaire de contact de l'application.
