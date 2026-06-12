import Handlebars from 'handlebars';
import { Article, Categorie } from './types';

const articlesMock: Article[] = [
  { id: 1, titre: "Découverte de l'architecture Docker", date: "2026-06-10T14:30:00", auteur: "Alan Turing", resume: "Un premier aperçu...", contenu: "...", categorie: "DevOps", url: "#/articles/1" },
  { id: 2, titre: "Pourquoi utiliser TypeScript ?", date: "2026-06-12T09:15:00", auteur: "Ada Lovelace", resume: "Le typage statique...", contenu: "...", categorie: "Frontend", url: "#/articles/2" },
  { id: 3, titre: "Maîtriser Handlebars en 5 minutes", date: "2026-06-11T18:00:00", auteur: "Grace Hopper", resume: "Créer des templates...", contenu: "...", categorie: "Frontend", url: "#/articles/3" }
];

const categoriesMock: Categorie[] = [
  { id: 1, nom: "DevOps", slug: "devops" },
  { id: 2, nom: "Frontend", slug: "frontend" },
  { id: 3, nom: "Backend", slug: "backend" }
];




// Fonction pour afficher une liste d'articles (tous, ou filtrés)
function renderArticles(articlesAafficher: Article[]) {
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    // Tri par date
    const articlesTries = [...articlesAafficher].sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());

    const templateSource = `
        <h2>Articles</h2>
        {{#if articles.length}}
            <div class="articles-list">
                {{#each articles}}
                <article class="article-item">
                    <h3><a href="{{this.url}}">{{this.titre}}</a></h3>
                    <div class="article-meta">
                        <span><strong>Catégorie :</strong> {{this.categorie}}</span> | 
                        <span><strong>Auteur :</strong> {{this.auteur}}</span> | 
                        <span><strong>Date :</strong> {{this.date}}</span>
                    </div>
                </article>
                {{/each}}
            </div>
        {{else}}
            <p>Aucun article dans cette catégorie.</p>
        {{/if}}
    `;
    
    const template = Handlebars.compile(templateSource);
    appContainer.innerHTML = template({ articles: articlesTries });
}



function renderCategories() {
    const sidebarContainer = document.getElementById('sidebar');
    if (!sidebarContainer) return;

    const templateSource = `
        <h2>Catégories</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 0.5rem;">
                <a href="#/" style="text-decoration: none; color: #333; font-weight: bold;">Tous les articles</a>
            </li>
            {{#each categories}}
            <li style="margin-bottom: 0.5rem;">
                <a href="#/categories/{{this.slug}}" style="text-decoration: none; color: #007BFF; font-weight: bold;">
                    {{this.nom}}
                </a>
            </li>
            {{/each}}
        </ul>
    `;
    const template = Handlebars.compile(templateSource);
    sidebarContainer.innerHTML = template({ categories: categoriesMock });
}


function handleNavigation() {
    const hash = window.location.hash;

    if (hash.startsWith('#/categories/')) {
        const slug = hash.replace('#/categories/', '');
        
        const articlesFiltres = articlesMock.filter(
            article => article.categorie.toLowerCase() === slug.toLowerCase()
        );
        renderArticles(articlesFiltres);
    } 
    else {
        renderArticles(articlesMock);
    }
}


document.addEventListener('DOMContentLoaded', () => {
    renderCategories();
    
    handleNavigation();

    window.addEventListener('hashchange', handleNavigation);
});