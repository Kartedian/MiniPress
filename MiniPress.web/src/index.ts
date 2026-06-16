import Handlebars from 'handlebars';
import { fetchArticles, fetchCategories, fetchArticle, fetchArticlesByCategorie , fetchArticlesByAuteur } from './api';


let currentSortOrder: 'asc' | 'desc' = 'desc';

let currentKeyword: string = '';


async function renderArticles(fetchPromise: Promise<any[]>) {
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    appContainer.innerHTML = '<p>Chargement des articles en cours...</p>';

    try {
        const articles = await fetchPromise;

        

        // On récupère les détails de chaque article pour obtenir le résumé
        const articlesComplets = await Promise.all(
            articles.map(async (art) => {
                // On extrait l'ID de l'URL pour pouvoir faire le fetch individuel
                const idExtrait = art.url ? art.url.split('/').pop() : '';
                
                try {
                    if (idExtrait) {
                        // 'Appel a l'API pour obtenir les détails de l'article
                        const details = await fetchArticle(idExtrait);
                        return { ...art, ...details, idExtrait };
                    }
                    return { ...art, idExtrait };
                } catch (err) {
                    console.error(`Impossible de charger les détails de l'article ${idExtrait}`);
                    return { ...art, idExtrait };
                }
            })
        );



        // Filtrage des articles en fonction du mot-clé de recherche
        const articlesFiltresParMotCle = articlesComplets.filter(art => {
            if (!currentKeyword) return true; 
            
            const recherche = currentKeyword.toLowerCase();
            const titreContient = art.titre ? art.titre.toLowerCase().includes(recherche) : false;
            
            const texteResume = art.resumer ? art.resumer.toLowerCase().includes(recherche) : false;

            return titreContient || texteResume;
        });




        // Tri des articles filtrés par date
        const articlesTries = articlesFiltresParMotCle.sort((a, b) => {
            const dateA = new Date(typeof a.date === 'string' ? a.date : a.date?.date).getTime();
            const dateB = new Date(typeof b.date === 'string' ? b.date : b.date?.date).getTime();
            return currentSortOrder === 'desc' ? dateB - dateA : dateA - dateB;
        });

        
        // Formatage des articles pour le template
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
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                <h2>Articles</h2>
                <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    
                    <div>
                        <label for="searchInput"><strong>Recherche : </strong></label>
                        <input type="text" id="searchInput" value="{{keyword}}" placeholder="Titre ou résumé..." style="padding: 0.3rem; width: 200px;">
                    </div>

                    <div>
                        <label for="sortOrder"><strong>Trier : </strong></label>
                        <select id="sortOrder" style="padding: 0.3rem;">
                            <option value="desc" {{#if isDesc}}selected{{/if}}>Plus récents</option>
                            <option value="asc" {{#if isAsc}}selected{{/if}}>Plus anciens</option>
                        </select>
                    </div>
                </div>
            </div>

            {{#if articles.length}}
                <div class="articles-list">
                    {{#each articles}}
                    <article class="article-item">
                        <h3><a href="{{this.frontendUrl}}">{{this.titre}}</a></h3>
                        <p style="color: #555; margin: 0.5rem 0;"><em>{{this.resumeAffiche}}</em></p>
                        <div class="article-meta">
                            <span><strong>Auteur :</strong> <a href="#/auteurs/{{this.auteurId}}" style="color: #007BFF; text-decoration: none;">{{this.auteurNom}}</a></span> | 
                            <span><strong>Date :</strong> {{this.date}}</span>
                        </div>
                    </article>
                    {{/each}}
                </div>
            {{else}}
                <p>Aucun article ne correspond à votre recherche.</p>
            {{/if}}
        `;
        
        const template = Handlebars.compile(templateSource);

        appContainer.innerHTML = template({ 
            articles: articlesFormates,
            isDesc: currentSortOrder === 'desc',
            isAsc: currentSortOrder === 'asc',
            keyword: currentKeyword 
        });


        // Ajout de l'écouteur/evenement pour le select de tri
        const sortSelect = document.getElementById('sortOrder') as HTMLSelectElement;
        if (sortSelect) {
            sortSelect.addEventListener('change', (event) => {
                currentSortOrder = (event.target as HTMLSelectElement).value as 'asc' | 'desc';
                handleNavigation(); 
            });
        }

        // Ajout de l'écouteur/evenement pour le champ de recherche
        const searchInput = document.getElementById('searchInput') as HTMLInputElement;
        if (searchInput) {
            searchInput.addEventListener('change', (event) => {
                currentKeyword = (event.target as HTMLInputElement).value;
                handleNavigation(); 
            });
        }

    } catch (error) {
        console.error(error);
        appContainer.innerHTML = '<p style="color: red;">Erreur de connexion à l\'API pour les articles.</p>';
    }
}






// Fonction pour afficher les catégories dans la sidebar
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



// Fonction pour afficher un article unique
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