// On importe Handlebars si tu utilises un bundler comme Vite ou Webpack
import Handlebars from 'handlebars';

// 1. Définition de l'interface qui correspond exactement au JSON de ton API
interface Article {
    titre: string;
    date: string;
    auteur: string;
    url: string;
}

/**
 * Récupère, trie et affiche les articles
 */
async function loadAndDisplayArticles(): Promise<void> {
    try {
        // Remplacer l'URL par la vraie route de ton API Dockerisée (ex: http://localhost:8080/api/articles)
        const response = await fetch('URL_DE_TON_API/articles'); 
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        // Typage direct du JSON retourné par l'API
        const articles: Article[] = await response.json();

        // 2. Tri des articles par ordre chronologique inverse (du plus récent au plus ancien)
        articles.sort((a: Article, b: Article) => {
            const dateA = new Date(a.date).getTime();
            const dateB = new Date(b.date).getTime();
            return dateB - dateA; 
        });

        // 3. Compilation du template Handlebars
        // Si tu utilises une balise script dans le HTML : const source = document.getElementById('template-articles').innerHTML;
        // Ici, je le mets en chaîne de caractères pour l'exemple
        const templateSource = `
            <div class="articles-list">
                <h2>Derniers articles</h2>
                <ul>
                    {{#each articles}}
                    <li class="article-item" style="margin-bottom: 1rem; border-bottom: 1px solid #ccc;">
                        <h3><a href="{{this.url}}">{{this.titre}}</a></h3>
                        <div class="article-meta">
                            <span><strong>Auteur ID :</strong> {{this.auteur}}</span> | 
                            <span><strong>Date :</strong> {{this.date}}</span>
                        </div>
                    </li>
                    {{/each}}
                </ul>
            </div>
        `;

        const template = Handlebars.compile(templateSource);
        
        // On passe les données triées au template
        const htmlContext = template({ articles: articles });

        // 4. Injection dans le DOM
        // Assure-toi d'avoir un <div id="app"></div> dans ton fichier index.html principal
        const appContainer = document.getElementById('app');
        if (appContainer) {
            appContainer.innerHTML = htmlContext;
        }

    } catch (error) {
        console.error("Impossible de charger les articles :", error);
    }
}

// Exécuter la fonction une fois que la page est chargée
document.addEventListener('DOMContentLoaded', loadAndDisplayArticles);