# Todo App - Application de Gestion de Tâches

Application full-stack de gestion de tâches construite avec Symfony, Vue.js et PostgreSQL.

## 🚀 Technologies utilisées

### Backend
- Symfony 6.4
- Doctrine ORM
- PostgreSQL 15
- API REST

### Frontend
- Vue 3 (Composition API)
- Vuetify 3
- Pinia (state management)
- Axios
- Vite

## 📋 Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js 18+ et npm
- Docker et Docker Compose (pour PostgreSQL)

## 🛠️ Installation

### 1. Cloner le repository

```bash
git clone <repository-url>
cd Test-technique
```

### 2. Démarrer PostgreSQL

```bash
docker-compose up -d
```

Vérifiez que PostgreSQL est bien démarré :
```bash
docker-compose ps
```

### 3. Configuration du Backend

```bash
cd backend

# Installer les dépendances
composer install

# Créer la base de données et les tables
php bin/console doctrine:database:create
php bin/console doctrine:schema:create

# Optionnel: Charger des données de test
# Vous pouvez créer manuellement quelques utilisateurs via l'interface web
```

### 4. Configuration du Frontend

```bash
cd ../frontend

# Installer les dépendances
npm install
```

## 🏃 Lancement de l'application

### Backend (Terminal 1)

```bash
cd backend
php -S localhost:8000 -t public
```

Le backend sera accessible sur `http://localhost:8000`

### Frontend (Terminal 2)

```bash
cd frontend
npm run dev
```

Le frontend sera accessible sur `http://localhost:5173`

## 📱 Utilisation

1. Ouvrez votre navigateur sur `http://localhost:5173`
2. Créez un compte utilisateur via l'onglet "Inscription"
3. Connectez-vous avec vos identifiants
4. Vous serez redirigé vers le tableau de bord
5. Créez, modifiez et gérez vos tâches

### Fonctionnalités disponibles

- ✅ Authentification (inscription/connexion)
- ✅ Création de tâches avec titre, description et utilisateur assigné
- ✅ Modification des tâches
- ✅ Suppression des tâches
- ✅ Changement de statut (À faire, En cours, Terminé)
- ✅ Filtrage par statut
- ✅ Recherche par titre
- ✅ Interface responsive et moderne avec Vuetify

## 🧪 Lancer les tests

```bash
cd backend
php vendor/bin/phpunit
```

## 🏗️ Structure du projet

```
Test-technique/
├── backend/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Controller/
│   │   ├── Entity/
│   │   └── Repository/
│   └── tests/
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── router/
│   │   └── store/
│   └── public/
└── docker-compose.yml
```

## 🔧 Configuration

### Base de données

Les paramètres de connexion à la base de données se trouvent dans `backend/.env` :

```env
DATABASE_URL="postgresql://todouser:todopass@localhost:5432/tododb?serverVersion=15&charset=utf8"
```

### CORS

Le CORS est configuré dans `backend/public/index.php` pour accepter les requêtes depuis le frontend.

## 📝 API Endpoints

### Authentification
- `POST /api/register` - Créer un compte utilisateur
- `POST /api/login` - Se connecter
- `GET /api/users` - Liste des utilisateurs

### Tâches
- `GET /api/tasks` - Liste des tâches (avec filtres optionnels: `?status=todo&search=terme`)
- `POST /api/tasks` - Créer une tâche
- `GET /api/tasks/{id}` - Détails d'une tâche
- `PUT /api/tasks/{id}` - Modifier une tâche
- `DELETE /api/tasks/{id}` - Supprimer une tâche

## 🐛 Dépannage

### Erreur de connexion à PostgreSQL
```bash
# Vérifier que le container est démarré
docker-compose ps

# Redémarrer le container si nécessaire
docker-compose restart
```

### Erreur CORS
Assurez-vous que le backend est bien démarré sur le port 8000 et que le fichier `backend/public/index.php` contient bien les headers CORS.

### Port déjà utilisé
Si le port 8000 ou 5173 est déjà utilisé, vous pouvez modifier les ports :
- Backend : `php -S localhost:AUTRE_PORT -t public`
- Frontend : Modifier le port dans `frontend/vite.config.js`

## 📄 Licence

Ce projet est un exercice de test technique.
