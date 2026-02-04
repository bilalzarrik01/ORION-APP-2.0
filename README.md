# 🔖 LaraBookmarks

**LaraBookmarks** est une application Laravel qui permet aux utilisateurs de sauvegarder, organiser et rechercher leurs liens favoris grâce à des **catégories** et des **tags**.

---

## 🚀 Fonctionnalités

- Authentification (inscription / connexion / déconnexion)
- Middleware bloquant les comptes désactivés
- CRUD Catégories
- CRUD Liens
- Système de Tags (Many-to-Many)
- Recherche par titre
- Filtrage par catégorie ou tag
- Interface Blade avec layouts & composants

---

## 🛠 Stack Technique

- Laravel  
- PHP  
- MySQL  
- Blade  
- Eloquent ORM  
- Middleware  

---

## 🗄 Base de Données

| Table     | Description |
|----------|-------------|
| users    | Utilisateurs |
| categories | Catégories des liens |
| links    | Liens sauvegardés |
| tags     | Tags |
| link_tag | Table pivot (links ↔ tags) |

---

## 🔗 Relations

- User → hasMany → Categories  
- Category → hasMany → Links  
- Link ↔ belongsToMany ↔ Tags  

---

## 🛡 Middleware `is_active`

Si `is_active = false`, l'utilisateur ne peut pas se connecter.

**Message affiché :**  
> Votre compte est désactivé. Veuillez contacter l'administrateur.

---

## ⚙ Installation

```bash
git clone https://github.com/your-username/larabookmarks.git
cd larabookmarks
composer install
cp .env.example .env
php artisan key:generate
