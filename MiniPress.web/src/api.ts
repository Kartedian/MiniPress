import { Article, Categorie } from './types';

const API_URL = 'http://docketu.iutnc.univ-lorraine.fr::13887/api';  


// Méthode pour récupérer tous les articles depuis l'API
export async function fetchArticles(): Promise<Article[]> {
    const res = await fetch(`${API_URL}/articles`); 
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}


// Méthode pour récupérer un article spécifique depuis l'API
export async function fetchArticle(id: string): Promise<any> {
    const res = await fetch(`${API_URL}/articles/${id}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

//Methode pour récupérer les catégories depuis l'API
export async function fetchCategories(): Promise<Categorie[]> {
    const res = await fetch(`${API_URL}/categories`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}


// Méthode pour récupérer les articles d'une catégorie spécifique depuis l'API
export async function fetchArticlesByCategorie(id: string): Promise<any[]> {
    const res = await fetch(`${API_URL}/categories/${id}/articles`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}


// Méthode pour récupérer les articles d'un auteur spécifique depuis l'API
export async function fetchArticlesByAuteur(auteur: string): Promise<Article[]> {
    const res = await fetch(`${API_URL}/auteurs/${encodeURIComponent(auteur)}/articles`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}


