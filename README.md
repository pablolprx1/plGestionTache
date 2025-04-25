The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Gestion de Projet

Cette application est un gestionnaire de tâches développé avec Laravel. Elle permet de gérer des projets et leurs tâches associées via une interface utilisateur intuitive.

## Fonctionnalités

- Gestion des utilisateurs (inscription, connexion, réinitialisation de mot de passe)
- Création, modification et suppression de projets
- Gestion des tâches associées à chaque projet

## Prérequis

- PHP 8.2 ou supérieur
- Composer installé
- Node.js et npm installés
- Serveur local (comme WAMP ou Laravel Sail)
- MySQL 5.7 ou supérieur

## Installation

1. Clonez le dépôt :
```bash
git clone https://github.com/votre-repo/plGestionTache.git
cd plGestionTache
```

2. Installez les dépendances PHP :
```bash
composer install
```

3. Installez les dépendances JavaScript :
```bash
npm install
```

4. Copiez le fichier d'environnement :
```bash
cp .env.example .env
```

5. Générez la clé d'application :
```bash
php artisan key:generate
```

6. Configurez votre base de données dans le fichier `.env` :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plgestiontache
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

7. Exécutez les migrations :
```bash
php artisan migrate
```

8. Compilez les assets :
```bash
npm run dev
```

9. Démarrez le serveur de développement :
```bash
php artisan serve
```

L'application sera accessible à l'adresse : http://localhost:8000

## Structure de l'Application

### Pages et Composants

1. **Page d'accueil** (`/`)
   - Vue : `resources/views/welcome.blade.php`
   - Contrôleur : Routage direct
   - Description : Page d'accueil publique de l'application

2. **Tableau de bord** (`/dashboard`)
   - Vue : `resources/views/dashboard.blade.php`
   - Contrôleur : `DashboardController.php`
   - Fonctions principales :
     - `index()` : Affiche le tableau de bord de l'utilisateur
     - Affichage des projets récents et des tâches en cours

3. **Gestion des Projets** (`/projects`)
   - Vue : `resources/views/projects/`
     - `index.blade.php` : Liste des projets
     - `create.blade.php` : Création de projet
     - `edit.blade.php` : Modification de projet
     - `show.blade.php` : Détails du projet
   - Contrôleur : `ProjectController.php`
   - Fonctions principales :
     - `index()` : Liste tous les projets
     - `create()` : Affiche le formulaire de création
     - `store()` : Enregistre un nouveau projet
     - `edit()` : Affiche le formulaire de modification
     - `update()` : Met à jour un projet
     - `destroy()` : Supprime un projet

4. **Profil Utilisateur** (`/profile`)
   - Vue : `resources/views/profile/`
   - Contrôleur : `ProfileController.php`
   - Fonctions principales :
     - `edit()` : Affiche le formulaire de modification du profil
     - `update()` : Met à jour les informations du profil
     - `destroy()` : Supprime le compte utilisateur

5. **Gestion des Membres du Projet**
   - Contrôleur : `ProjectMemberController.php`
   - Fonctions principales :
     - `store()` : Ajoute un membre au projet
     - `destroy()` : Retire un membre du projet

### Composants Livewire

L'application utilise Livewire pour les interactions dynamiques dans :
- La gestion des tâches
- Les formulaires en temps réel
- Les mises à jour de statut

### Authentification

Le système d'authentification est géré par Laravel Breeze, avec les vues situées dans `resources/views/auth/`.

## Licence

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
