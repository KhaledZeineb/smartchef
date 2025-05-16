# SmartChef - Mon Frigo Intelligent 🍳

SmartChef est une application web développée avec Symfony 6 qui vous aide à réduire le gaspillage alimentaire et à optimiser l'utilisation des ingrédients disponibles dans votre frigo. Grâce à l'intégration d'une intelligence artificielle, l'application vous suggère des recettes adaptées aux ingrédients que vous avez déjà chez vous.

## Fonctionnalités principales

- 👤 **Authentification** : Inscription et connexion des utilisateurs
- 🥕 **Gestion des ingrédients** : Ajout, consultation, modification et suppression de vos ingrédients
- 🧠 **Intelligence artificielle** : Suggestion de recettes basées sur vos ingrédients
- 📱 **Interface responsive** : Utilisation sur mobile ou desktop

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- MySQL ou SQLite
- Une clé API OpenAI (pour la génération de recettes)

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-username/smartchef.git
   cd smartchef
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configurer les variables d'environnement**
   - Copiez le fichier `.env` en `.env.local`
   - Configurez la base de données dans `.env.local`
   - Ajoutez votre clé API OpenAI : `OPENAI_API_KEY=votre-clé-api`

4. **Créer la base de données**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Initialiser les ingrédients**
   ```bash
   php bin/console app:init-ingredients
   ```

6. **Lancer le serveur de développement**
   ```bash
   symfony server:start
   ```

7. **Accéder à l'application**
   - Ouvrez votre navigateur à l'adresse : `http://localhost:8000`

## Utilisation

1. **Inscription et connexion**
   - Créez un compte utilisateur
   - Connectez-vous avec vos identifiants

2. **Ajouter des ingrédients**
   - Allez dans "Mes Ingrédients"
   - Cliquez sur "Ajouter un ingrédient"
   - Sélectionnez l'ingrédient, sa quantité et son unité

3. **Générer une recette**
   - Depuis votre frigo, cliquez sur "Générer une recette"
   - L'IA vous proposera une recette adaptée à vos ingrédients

## Structure du projet

- `config/` : Configuration de l'application
- `src/` : Code source
  - `Controller/` : Contrôleurs de l'application
  - `Entity/` : Entités Doctrine (User, Ingredient, UserIngredient)
  - `Form/` : Formulaires
  - `Repository/` : Repositories pour l'accès aux données
  - `Service/` : Services, notamment l'intégration avec OpenAI
- `templates/` : Templates Twig pour les vues
- `migrations/` : Migrations de base de données

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à soumettre une pull request.

## Licence

Ce projet est sous licence [MIT](LICENSE).