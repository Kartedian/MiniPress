import '../models/article.dart';

class ArticleService {
  static final List<Article> _articles = [
    Article(
      id: 1,
      title: 'Introduction à Flutter',
      content:
          'Flutter est un framework open-source développé par Google pour créer des applications multiplateformes à partir d\'un seul code source.',
      author: 'Alice Martin',
      createdAt: DateTime(2024, 1, 10),
      category: 'Technologie',
    ),
    Article(
      id: 2,
      title: 'Les bases du Dart',
      content:
          'Dart est le langage de programmation utilisé par Flutter. Il est orienté objet et compilé en code natif.',
      author: 'Bob Dupont',
      createdAt: DateTime(2024, 2, 5),
      category: 'Programmation',
    ),
    Article(
      id: 3,
      title: 'Docker pour les développeurs',
      content:
          'Docker permet de créer des conteneurs légers et portables pour déployer des applications dans n\'importe quel environnement.',
      author: 'Claire Lefebvre',
      createdAt: DateTime(2024, 3, 18),
      category: 'DevOps',
    ),
    Article(
      id: 4,
      title: 'API REST avec Laravel',
      content:
          'Laravel propose des outils puissants pour construire des API REST robustes et sécurisées en PHP.',
      author: 'David Bernard',
      createdAt: DateTime(2024, 4, 2),
      category: 'Web',
    ),
    Article(
      id: 5,
      title: 'Gestion d\'état avec Provider',
      content:
          'Provider est l\'une des solutions de gestion d\'état les plus populaires dans l\'écosystème Flutter.',
      author: 'Alice Martin',
      createdAt: DateTime(2024, 5, 20),
      category: 'Technologie',
    ),
    Article(
      id: 6,
      title: 'Introduction à SQL',
      content:
          'SQL est un langage standardisé pour interagir avec les bases de données relationnelles.',
      author: 'Émilie Rousseau',
      createdAt: DateTime(2024, 6, 14),
      category: 'Base de données',
    ),
  ];

  /// Retourne les articles triés par date décroissante.
  List<Article> getArticles() {
    final sorted = List<Article>.from(_articles);
    sorted.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    return sorted;
  }

  /// Retourne les articles d'une catégorie, triés par date décroissante.
  List<Article> getArticlesByCategory(String category) {
    final filtered = _articles.where((a) => a.category == category).toList();
    filtered.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    return filtered;
  }

  /// Retourne la liste des catégories uniques triées alphabétiquement.
  List<String> getCategories() {
    final categories = _articles.map((a) => a.category).toSet().toList();
    categories.sort();
    return categories;
  }

  /// Retourne les articles d'un auteur, triés par date décroissante.
  List<Article> getArticlesByAuthor(String author) {
    final filtered = _articles.where((a) => a.author == author).toList();
    filtered.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    return filtered;
  }

  Article? getArticleById(int id) {
    try {
      return _articles.firstWhere((a) => a.id == id);
    } catch (_) {
      return null;
    }
  }
}
