# Task Manager — Système de Gestion de Tâches
**Matar Gueye — Architecture Logicielle L3 — 2024/2025**

## Stack Technique
- **Backend** : Laravel 11 (PHP 8.2)
- **Base de données** : PostgreSQL
- **Authentification** : Laravel Sanctum (JWT)
- **Queue** : RabbitMQ (via Laravel Queue)
- **Documentation API** : L5-Swagger (OpenAPI)

---

## Installation & Lancement

### Prérequis
- PHP >= 8.2
- Composer
- PostgreSQL
- Node.js (optionnel, pour le frontend)

### 1. Cloner le projet
```bash
git clone https://github.com/matargueye/task-manager.git
cd task-manager
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

Modifier `.env` :
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=taskmanager
DB_USERNAME=postgres
DB_PASSWORD=your_password

QUEUE_CONNECTION=database  # ou rabbitmq
```

### 4. Migrer la base de données
```bash
php artisan migrate
php artisan db:seed  # Données de test optionnelles
```

### 5. Lancer le serveur
```bash
php artisan serve
# API disponible sur http://localhost:8000/api
```

### 6. Lancer le worker de queue (notifications asynchrones)
```bash
php artisan queue:work --queue=notifications
```

---

## Documentation API (Swagger)
```bash
php artisan l5-swagger:generate
# Accéder à : http://localhost:8000/api/documentation
```

---

## Endpoints Principaux

| Méthode | Endpoint                  | Description              | Auth |
|---------|---------------------------|--------------------------|------|
| POST    | /api/auth/register        | Inscription              | Non  |
| POST    | /api/auth/login           | Connexion + token        | Non  |
| GET     | /api/tasks                | Liste des tâches         | Oui  |
| POST    | /api/tasks                | Créer une tâche          | Oui  |
| PUT     | /api/tasks/{id}           | Modifier une tâche       | Oui  |
| DELETE  | /api/tasks/{id}           | Supprimer une tâche      | Oui  |
| GET     | /api/categories           | Liste des catégories     | Oui  |
| GET     | /api/notifications        | Notifications            | Oui  |
| GET     | /api/health               | Health check             | Non  |

### Authentification
Inclure le token dans le header de chaque requête :
```
Authorization: Bearer {votre_token}
```

---

## Architecture du Projet

```
app/
├── Http/
│   ├── Controllers/     # Couche Présentation
│   ├── Requests/        # Validation des données
│   └── Resources/       # DTO (Data Transfer Objects)
├── Services/            # Couche Métier (logique business)
├── Repositories/        # Couche Accès Données
│   └── Interfaces/      # Contrats (Inversion de Dépendance)
├── Models/              # Entités Eloquent
├── Events/              # Événements domaine
└── Listeners/           # Gestionnaires d'événements (async)
```

## Patterns Utilisés
- **Repository Pattern** : découplage entre logique métier et accès BDD
- **Service Layer** : centralisation de la logique métier
- **DTO (API Resources)** : contrôle des données exposées par l'API
- **Dependency Injection** : via le Service Container de Laravel
- **Observer/Event** : pour les notifications asynchrones
- **SOLID** : SRP, OCP, LSP, ISP, DIP respectés

---

## Tests
```bash
php artisan test
# ou
./vendor/bin/pest
```

---

## Structure Microservices (Phase 3)

Pour la version microservices, le système est découpé en :
- `user-service` (port 8001) — Gestion des identités
- `task-service` (port 8002) — Gestion des tâches
- `notification-service` (port 8003) — Notifications

Chaque service possède sa propre base de données PostgreSQL.
La communication asynchrone passe par RabbitMQ.
