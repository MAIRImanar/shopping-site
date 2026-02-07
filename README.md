# 🛍️ ShopHub - Site de Shopping en Ligne

Site e-commerce complet développé en HTML, CSS, JavaScript et PHP avec base de données MySQL.

## 📋 Fonctionnalités

### Frontend
- ✅ Page d'accueil avec produits en vedette
- ✅ Navigation par catégories
- ✅ Page de détails produit
- ✅ Panier d'achat dynamique (AJAX)
- ✅ Système d'authentification (inscription/connexion)
- ✅ Design responsive et moderne
- ✅ Animations et transitions fluides

### Backend
- ✅ Gestion des produits
- ✅ Gestion des catégories
- ✅ Système de panier avec session
- ✅ Authentification utilisateur sécurisée
- ✅ Base de données MySQL
- ✅ Requêtes AJAX pour le panier

## 👤 Compte de test

Un compte administrateur par défaut est créé :
- **Username:** admin
- **Email:** admin@shop.com
- **Password:** admin123

## 📂 Structure du projet

```
shopping-site/
├── ajax/                   # Scripts AJAX
│   ├── add_to_cart.php
│   ├── update_cart.php
│   └── remove_from_cart.php
├── css/                    # Fichiers CSS
│   └── style.css
├── includes/               # Fichiers PHP réutilisables
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── js/                     # Fichiers JavaScript
│   └── main.js
├── index.php              # Page d'accueil
├── product.php            # Page détails produit
├── cart.php               # Page panier
├── category.php           # Page catégorie
├── login.php              # Page connexion
├── register.php           # Page inscription
├── logout.php             # Script de déconnexion
└── database.sql           # Script SQL de la base de données
```

### Logo et nom du site
Changez "ShopHub" dans les fichiers header.php et index.php

## 🔐 Sécurité

- ✅ Mots de passe hashés avec `password_hash()`
- ✅ Protection contre les injections SQL avec PDO et requêtes préparées
- ✅ Nettoyage des données utilisateur avec `htmlspecialchars()`
- ✅ Sessions sécurisées
- ✅ Validation côté serveur

## 📱 Responsive Design

Le site est entièrement responsive et s'adapte à :
- 📱 Mobile (320px et plus)
- 📱 Tablette (768px et plus)
- 💻 Desktop (1024px et plus)

## 🛠️ Technologies utilisées

- **Frontend:**
  - HTML5
  - CSS3 (Variables CSS, Flexbox, Grid)
  - JavaScript (ES6+)
  - AJAX (Fetch API)

- **Backend:**
  - PHP 7.4+
  - MySQL 5.7+
  - PDO pour la base de données

## 📊 Base de données

Tables principales :
- `users` - Utilisateurs
- `products` - Produits
- `categories` - Catégories
- `orders` - Commandes
- `order_items` - Détails des commandes

## 🎯 Fonctionnalités à venir (Extensions possibles)

- [ ] Page de paiement
- [ ] Historique des commandes
- [ ] Panel d'administration complet
- [ ] Recherche avancée
- [ ] Filtres de produits
- [ ] Système de notes et avis
- [ ] Upload d'images de produits
- [ ] Gestion des stocks en temps réel
- [ ] Notifications par email
- [ ] Export de factures PDF

## 📝 Notes

- Les images de produits utilisent des emojis (📦) pour la démo
- Pour utiliser de vraies images, créez un dossier `images/products/` et mettez à jour les URLs
- Le panier utilise les sessions PHP pour stocker les données
- Les prix sont en DA (Dinar Algérien) - modifiez selon vos besoins

