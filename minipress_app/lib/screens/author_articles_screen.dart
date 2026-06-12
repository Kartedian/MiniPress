import 'package:flutter/material.dart';
import '../models/article.dart';
import '../services/article_service.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class AuthorArticlesScreen extends StatefulWidget {
  final String author;

  const AuthorArticlesScreen({super.key, required this.author});

  @override
  State<AuthorArticlesScreen> createState() => _AuthorArticlesScreenState();
}

class _AuthorArticlesScreenState extends State<AuthorArticlesScreen> {
  bool _sortAscending = false;

  List<Article> get _articles {
    final base = ArticleService().getArticlesByAuthor(widget.author);
    if (_sortAscending) {
      return List.from(base)..sort((a, b) => a.createdAt.compareTo(b.createdAt));
    }
    return base;
  }

  void _toggleSort() => setState(() => _sortAscending = !_sortAscending);

  @override
  Widget build(BuildContext context) {
    final articles = _articles;

    return Scaffold(
      appBar: AppBar(
        title: Text(widget.author),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        actions: [
          Tooltip(
            message: _sortAscending ? 'Plus ancien en premier' : 'Plus récent en premier',
            child: IconButton(
              icon: Icon(_sortAscending ? Icons.arrow_upward : Icons.arrow_downward),
              onPressed: _toggleSort,
            ),
          ),
        ],
      ),
      body: articles.isEmpty
          ? const Center(child: Text('Aucun article pour cet auteur.'))
          : ListView.builder(
              itemCount: articles.length,
              itemBuilder: (context, index) {
                final Article article = articles[index];
                return ArticleTile(
                  article: article,
                  onTap: () => Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => ArticleDetailScreen(article: article),
                    ),
                  ),
                );
              },
            ),
    );
  }
}
