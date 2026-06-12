export interface Article {
    id: number;
    titre: string;
    date: string;
    auteur: string;
    resume: string;
    contenu: string;
    categorie: string;
    url?: string;
}

export interface Categorie {
    id: number;
    nom: string;
    slug: string;
}

export interface Auteur {
    id: number;
    nom: string;
}
