# MiniPress

## Partie Architecture / Backend

### Description

L’objectif est de développer MiniPress.core, un mini CMS « headless » qui permet de créer et stocker
des articles et de les rendre disponibles en ligne au travers d’une api.
MiniPress.core comprend deux composants principaux :
- Une api mettant à disposition les articles au format JSON. L’api est librement utilisable et
permet uniquement de naviguer, rechercher et consulter l’ensemble des articles,
- Une partie Admin permettant de créer des articles en saisissant les données dans des
formulaires HTML. Cette partie admin est réservée aux utilisateurs enregistrés.

| Fonctionnalité                                        | État | Développeur |
| ------------------------------------------------------| ---- | ----------- |
| 1. Création d'un article                              | ✅ |     Walid    |
| 2. Création d'un article avec sélection de catégorie  | ✅ |     Walid    |
| 3. Liste des articles triés par date décroissante     | ✅ |     Walid    |
| 4. Filtrage des articles par catégorie                | ✅ |     Walid    |
| 5. Création de catégories                             | ✅ |     Walid    |
| 6. Authentification des utilisateurs                  | ✅ |     Walid    |
| 7. Contrôle d'accès aux fonctionnalités protégées     | ✅ |     Walid    |
| 8. Gestion des auteurs des articles                   | ✅ |     Walid    |
| 9. API - Liste des catégories                         | ✅ | Alexandre |
| 10. API - Liste des articles                          | ✅ | Alexandre |
| 11. API - Articles d'une catégorie                    | ✅ | Alexandre |
| 12. API - Article complet                             | ✅ | Alexandre |
| 13. API - Articles d'un auteur                        | ✅ | Alexandre |
| 14. Publication / Dépublication des articles          | ✅ |     Walid    |
| 15. Création d'utilisateurs par l'administrateur      | ✅ |     Walid    |
| 16. Tri des articles dans l'API (date/auteur)         | ✅ | Alexandre |

---

## Partie Web (JavaScript)

### Description

L’objectif est de développer MiniPress.web, une webapp fonctionnant dans le navigateur et
permettant de parcourir et visualiser les articles proposés par l’api MiniPress.core.
L’application doit permettre de naviguer dans les catégories et les listes d’articles et afficher un article
sélectionné. L’application effectue des requêtes asynchrones vers l’api MiniPress.core, récupère les
données en format JSON et les insère dans l’interface web. C’est elle qui réalise la conversion des
contenus d’articles en markdown vers du HTML.
MiniPress.web doit être hébergée et servie par un serveur web intégré à la composition de services
docker de MiniPress.core.

| Fonctionnalité                                      | État | Développeur |
| --------------------------------------------------- | ---- | ----------- |
| 1. Affichage de la liste des articles                  |      |             |
| 2. Affichage de la liste des catégories                |      |             |
| 3. Affichage des articles d'une catégorie              |      |             |
| 4. Affichage complet d'un article                      |      |             |
| 5. Affichage des articles d'un auteur                  |      |             |
| 6. Tri des articles par date (croissant / décroissant) |      |             |
| 7. Recherche par mot-clé dans le titre                 |      |             |
| 8. Recherche par mot-clé dans le titre ou le résumé    |      |             |

---

## Partie Mobile (Flutter)

### Description

L’objectif est de développer MiniPress.app, une application mobile développée avec Flutter et
permettant de parcourir et visualiser les articles proposés par l’api MiniPress.core. L’application
mobile doit permettre de naviguer dans les catégories et les listes d’articles et afficher un article
sélectionné. L’application mobile, de type « master / détail » (cf. projet « ToDo List »), effectue des
requêtes asynchrones vers l’api MiniPress.core, récupère les données en format JSON et les insère
dans l’interface utilisateur.

| Fonctionnalité                                      | État | Développeur |
| --------------------------------------------------- | ---- | ----------- |
| 1. Affichage de la liste des articles                  |      |             |
| 2. Affichage de la liste des catégories                |      |             |
| 3. Affichage des articles d'une catégorie              |      |             |
| 4. Affichage complet d'un article                      |      |             |
| 5. Affichage des articles d'un auteur                  |      |             |
| 6. Tri des articles par date (croissant / décroissant) |      |             |
| 7. Recherche par mot-clé dans le titre                 |      |             |
| 8. Recherche par mot-clé dans le titre ou le résumé    |      |             |

---

## Équipe

*  Walid BOUAOUKEL
*  Alexandre CHERRIER
*  Matteo GRENIER
*  Jules ANDRE
