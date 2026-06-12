import { Article, Categorie } from './types';

const API_URL = 'http://localhost:8080/api';

export async function fetchArticles(): Promise<Article[]> {
    const res = await fetch(`${API_URL}/articles`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

export async function fetchArticle(id: number): Promise<Article> {
    const res = await fetch(`${API_URL}/articles/${id}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

export async function fetchCategories(): Promise<Categorie[]> {
    const res = await fetch(`${API_URL}/categories`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

export async function fetchArticlesByCategorie(slug: string): Promise<Article[]> {
    const res = await fetch(`${API_URL}/categories/${slug}/articles`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

export async function fetchArticlesByAuteur(auteur: string): Promise<Article[]> {
    const res = await fetch(`${API_URL}/auteurs/${encodeURIComponent(auteur)}/articles`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}
