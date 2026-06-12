import Handlebars from 'handlebars';
import { Article } from './types';

// On met les données directement ici pour esquiver le serveur
const articlesMock: Article[] = [
  {
    id: 1,
    titre: "Découverte de l'architecture Docker",
    date: "2026-06-10T14:30:00",
    auteur: "Alan Turing",
    resume: "Un premier aperçu de la conteneurisation...",
    contenu: "Contenu complet ici...",
    categorie: "DevOps",
    url: "/articles/1"
  },
  {
    id: 2,
    titre: "Pourquoi utiliser TypeScript ?",
    date: "2026-06-12T09:15:00",
    auteur: "Ada Lovelace",
    resume: "Le typage statique sauve des vies.",
    contenu: "Contenu complet ici...",
    categorie: "Frontend",
    url: "/articles/2"
  },
  {
    id: 3,
    titre: "Maîtriser Handlebars en 5 minutes",
    date: "2026-06-11T18:00:00",
    auteur: "Grace Hopper",
    resume: "Créer des templates dynamiques.",
    contenu: "Contenu complet ici...",
    categorie: "Frontend",
    url: "/articles/3"
  }
];

document.addEventListener('DOMContentLoaded', () => {
    const appContainer = document.getElementById('app');
    if (!appContainer) return;

    // 1. On utilise directement notre tableau
    const articles = articlesMock;

    // 2. Tri par ordre chronologique inverse
    articles.sort((a, b) => {
        const dateA = new Date(a.date).getTime();
        const dateB = new Date(b.date).getTime();
        return dateB - dateA; 
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

    const template = Handlebars.compile(templateSource);
    appContainer.innerHTML = template({ articles: articles });
});