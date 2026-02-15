#!/bin/bash

echo "🚀 Installation du projet Todo App - Test Technique"
echo "=================================================="

# Couleurs pour les messages
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Vérifier les prérequis
echo ""
echo "📋 Vérification des prérequis..."

command -v php >/dev/null 2>&1 || { echo -e "${RED}❌ PHP n'est pas installé${NC}"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo -e "${RED}❌ Composer n'est pas installé${NC}"; exit 1; }
command -v node >/dev/null 2>&1 || { echo -e "${RED}❌ Node.js n'est pas installé${NC}"; exit 1; }
command -v npm >/dev/null 2>&1 || { echo -e "${RED}❌ npm n'est pas installé${NC}"; exit 1; }
command -v docker >/dev/null 2>&1 || { echo -e "${RED}❌ Docker n'est pas installé${NC}"; exit 1; }

echo -e "${GREEN}✅ Tous les prérequis sont installés${NC}"

# Démarrer PostgreSQL
echo ""
echo "🐳 Démarrage de PostgreSQL avec Docker..."
docker-compose up -d
sleep 5

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ PostgreSQL démarré${NC}"
else
    echo -e "${RED}❌ Erreur au démarrage de PostgreSQL${NC}"
    exit 1
fi

# Installation Backend
echo ""
echo "📦 Installation du backend Symfony..."
cd backend

composer install --no-interaction --prefer-dist

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dépendances backend installées${NC}"
else
    echo -e "${RED}❌ Erreur lors de l'installation des dépendances backend${NC}"
    exit 1
fi

# Créer la base de données
echo ""
echo "🗄️ Création de la base de données..."
php bin/console doctrine:database:create --if-not-exists

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Base de données créée${NC}"
else
    echo -e "${YELLOW}⚠️ La base de données existe peut-être déjà${NC}"
fi

# Créer les tables
echo ""
echo "📊 Création des tables..."
php bin/console doctrine:schema:create

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Tables créées${NC}"
else
    echo -e "${YELLOW}⚠️ Les tables existent peut-être déjà${NC}"
fi

cd ..

# Installation Frontend
echo ""
echo "📦 Installation du frontend Vue.js..."
cd frontend

npm install

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dépendances frontend installées${NC}"
else
    echo -e "${RED}❌ Erreur lors de l'installation des dépendances frontend${NC}"
    exit 1
fi

cd ..

# Résumé
echo ""
echo "=================================================="
echo -e "${GREEN}✅ Installation terminée !${NC}"
echo ""
echo "Pour lancer l'application :"
echo ""
echo "Terminal 1 - Backend :"
echo "  cd backend"
echo "  php -S localhost:8000 -t public"
echo ""
echo "Terminal 2 - Frontend :"
echo "  cd frontend"
echo "  npm run dev"
echo ""
echo "Ensuite, ouvrez votre navigateur sur : http://localhost:5173"
echo ""
echo "=================================================="
