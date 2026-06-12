import Handlebars from 'handlebars';
import { fetchArticles } from './api';
import { Article } from './types';

document.addEventListener('DOMContentLoaded', async () => {
    console.log('MiniPress.web chargé');
    
    // On cible la balise <main id="app"> de ton index.html
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    try {
        // 1. Récupération des données mockées
        const articles = await fetchArticles();

        // 2. Tri par ordre chronologique inverse (le plus récent en premier)
        articles.sort((a: Article, b: Article) => {
            const dateA = new Date(a.date).getTime();
            const dateB = new Date(b.date).getTime();
            return dateB - dateA; // Si on voulait du plus ancien au plus récent, on ferait dateA - dateB
        });

        // 3. Création du template Handlebars
        const templateSource = `
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

        // 4. Compilation et injection
        const template = Handlebars.compile(templateSource);
        const html = template({ articles: articles });
        
        appContainer.innerHTML = html;

    } catch (error) {
        console.error("Erreur de chargement :", error);
        appContainer.innerHTML = `<p style="color: red;">Impossible de charger les articles pour le moment.</p>`;
    }
});