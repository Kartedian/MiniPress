import 'package:flutter/material.dart';
import '../models/article.dart';
import '../services/article_service.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class ArticleListScreen extends StatelessWidget {
  const ArticleListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final articles = ArticleService().getArticles();

    return Scaffold(
      appBar: AppBar(
        title: const Text('MiniPress'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: ListView.builder(
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
