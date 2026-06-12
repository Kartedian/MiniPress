import Handlebars from 'handlebars';
import { Article, Categorie } from './types';

// On met les données directement ici pour esquiver le serveur
const articlesMock: Article[] = [
  { id: 1, titre: "Découverte de l'architecture Docker", date: "2026-06-10T14:30:00", auteur: "Alan Turing", resume: "Un premier aperçu...", contenu: "...", categorie: "DevOps", url: "/articles/1" },
  { id: 2, titre: "Pourquoi utiliser TypeScript ?", date: "2026-06-12T09:15:00", auteur: "Ada Lovelace", resume: "Le typage statique...", contenu: "...", categorie: "Frontend", url: "/articles/2" },
  { id: 3, titre: "Maîtriser Handlebars en 5 minutes", date: "2026-06-11T18:00:00", auteur: "Grace Hopper", resume: "Créer des templates...", contenu: "...", categorie: "Frontend", url: "/articles/3" }
];

const categoriesMock: Categorie[] = [
  { id: 1, nom: "DevOps", slug: "devops" },
  { id: 2, nom: "Frontend", slug: "frontend" },
  { id: 3, nom: "Backend", slug: "backend" }
];

document.addEventListener('DOMContentLoaded', () => {
    
    // --- GESTION DES ARTICLES ---
    const appContainer = document.getElementById('app');
    if (appContainer) {
        const articles = articlesMock;
        
        // Tri par date
        articles.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());

        const articlesTemplateSource = `
            <h2>Derniers articles</h2>
            <div class="articles-list">
                {{#each articles}}
                <article class="article-item">
                    <h3><a href="{{this.url}}">{{this.titre}}</a></h3>
                    <div class="article-meta">
                        <span><strong>Auteur :</strong> {{this.auteur}}</span> | 
                        <span><strong>Date :</strong> {{this.date}}</span>
                    </div>
                </article>
                {{/each}}
            </div>
        `;
        
        const articlesTemplate = Handlebars.compile(articlesTemplateSource);
        appContainer.innerHTML = articlesTemplate({ articles: articles });
    }

    // --- GESTION DES CATÉGORIES ---
    const sidebarContainer = document.getElementById('sidebar');
    if (sidebarContainer) {
        const categories = categoriesMock;

        const categoriesTemplateSource = `
            <h2>Catégories</h2>
            <ul style="list-style-type: none; padding-left: 0;">
                {{#each categories}}
                <li style="margin-bottom: 0.5rem;">
                    <a href="#/categories/{{this.slug}}" style="text-decoration: none; color: #007BFF; font-weight: bold;">
                        {{this.nom}}
                    </a>
                </li>
                {{/each}}
            </ul>
        `;

        const categoriesTemplate = Handlebars.compile(categoriesTemplateSource);
        sidebarContainer.innerHTML = categoriesTemplate({ categories: categories });
    }
});