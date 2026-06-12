import Handlebars from 'handlebars';
import { fetchArticles, fetchCategories, fetchArticle, fetchArticlesByCategorie , fetchArticlesByAuteur } from './api';





async function renderArticles(fetchPromise: Promise<any[]>) {
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    appContainer.innerHTML = '<p>Chargement des articles en cours...</p>';

    try {
        const articles = await fetchPromise;

        const articlesTries = articles.sort((a, b) => {
            const dateA = typeof a.date === 'string' ? a.date : a.date?.date;
            const dateB = typeof b.date === 'string' ? b.date : b.date?.date;
            return new Date(dateB).getTime() - new Date(dateA).getTime();
        });

        const articlesFormates = articlesTries.map(art => {
            const idExtrait = art.url ? art.url.split('/').pop() : '';
            return {
                ...art,
                frontendUrl: `#/articles/${idExtrait}`,
                auteurId: art.auteur?.id || art.auteur, 
                auteurNom: art.auteur?.name || art.auteur || 'Inconnu'
            };
        });

        const templateSource = `
            <h2>Articles</h2>
            {{#if articles.length}}
                <div class="articles-list">
                    {{#each articles}}
                    <article class="article-item">
                        <h3><a href="{{this.frontendUrl}}">{{this.titre}}</a></h3>
                        <div class="article-meta">
                            <span><strong>Auteur :</strong> <a href="#/auteurs/{{this.auteurId}}" style="color: #007BFF; text-decoration: none;">{{this.auteurNom}}</a></span> | 
                            <span><strong>Date :</strong> {{this.date}}</span>
                        </div>
                    </article>
                    {{/each}}
                </div>
            {{else}}
                <p>Aucun article trouvé.</p>
            {{/if}}
        `;
        
        const template = Handlebars.compile(templateSource);
        appContainer.innerHTML = template({ articles: articlesFormates });

    } catch (error) {
        console.error(error);
        appContainer.innerHTML = '<p style="color: red;">Erreur de connexion à l\'API pour les articles.</p>';
    }
}


async function renderCategories() {
    const sidebarContainer = document.getElementById('sidebar');
    if (!sidebarContainer) return;

    try {
        const categories = await fetchCategories();

        const categoriesFormatees = categories.map((cat: any) => ({
            id: cat.id || cat.ID || cat.id_categorie,
            nom: cat.nom || cat.Nom || cat.titre || cat.Titre || cat.libelle
        }));

        const templateSource = `
            <h2>Catégories</h2>
            <ul style="list-style-type: none; padding-left: 0;">
                <li style="margin-bottom: 0.5rem;">
                    <a href="#/" style="text-decoration: none; color: #333; font-weight: bold;">Tous les articles</a>
                </li>
                {{#each categories}}
                <li style="margin-bottom: 0.5rem;">
                    <a href="#/categories/{{this.id}}" style="text-decoration: none; color: #007BFF; font-weight: bold;">
                        {{this.nom}}
                    </a>
                </li>
                {{/each}}
            </ul>
        `;
        const template = Handlebars.compile(templateSource);
        sidebarContainer.innerHTML = template({ categories: categoriesFormatees });
    } catch (error) {
        console.error(error);
        sidebarContainer.innerHTML = '<p style="color: red;">Erreur chargement catégories.</p>';
    }
}



async function renderArticleUnique(idArticle: string) {
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    appContainer.innerHTML = '<p>Chargement de l\'article...</p>';

    try {
        const article = await fetchArticle(idArticle);

        const templateSource = `
            <article class="single-article">
                <a href="#/" style="display: inline-block; margin-bottom: 1rem; text-decoration: none; color: #007BFF;">&larr; Retour aux articles</a>
                
                <h2>{{titre}}</h2>
                
                <div class="article-meta" style="margin-bottom: 2rem; border-bottom: 1px solid #ccc; padding-bottom: 1rem;">
                    <span><strong>Catégorie :</strong> {{categorie}}</span> | 
                    <span><strong>Auteur :</strong> <a href="#/auteurs/{{auteur.id}}" style="color: #007BFF; text-decoration: none;">{{auteur.name}}</a></span> | 
                    <span><strong>Date :</strong> {{date.date}}</span>
                </div>
                
                <div class="article-content" style="line-height: 1.6; font-size: 1.1em;">
                    <p><em>{{resumer}}</em></p>
                    <p>{{contenue}}</p>
                </div>
            </article>
        `;

        const template = Handlebars.compile(templateSource);
        appContainer.innerHTML = template(article);

    } catch (error) {
        console.error(error);
        appContainer.innerHTML = `<p style="color: red;">Erreur : Impossible de charger cet article.</p><a href="#/">Retour</a>`;
    }
}




function handleNavigation() {
    const hash = window.location.hash;

    if (hash.startsWith('#/categories/')) {
        const paramCat = hash.replace('#/categories/', '');
        renderArticles(fetchArticlesByCategorie(paramCat));
    } 
    else if (hash.startsWith('#/auteurs/')) {
        const idAuteur = hash.replace('#/auteurs/', '');
        renderArticles(fetchArticlesByAuteur(idAuteur));
    }
    else if (hash.startsWith('#/articles/')) {
        const idArticle = hash.replace('#/articles/', '');
        renderArticleUnique(idArticle);
    }
    else {
        renderArticles(fetchArticles());
    }
}


document.addEventListener('DOMContentLoaded', () => {
    renderCategories();
    handleNavigation();
    window.addEventListener('hashchange', handleNavigation);
});