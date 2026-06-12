import 'package:flutter/material.dart';
import '../models/article.dart';
import '../services/article_service.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class AuthorArticlesScreen extends StatelessWidget {
  final String author;

  const AuthorArticlesScreen({super.key, required this.author});

  @override
  Widget build(BuildContext context) {
    final articles = ArticleService().getArticlesByAuthor(author);

    return Scaffold(
      appBar: AppBar(
        title: Text(author),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
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
