import 'package:flutter/material.dart';
import '../models/article.dart';
import '../services/article_service.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class ArticleListScreen extends StatefulWidget {
  const ArticleListScreen({super.key});

  @override
  State<ArticleListScreen> createState() => _ArticleListScreenState();
}

class _ArticleListScreenState extends State<ArticleListScreen> {
  final _service = ArticleService();
  String? _selectedCategory;

  List<Article> get _articles => _selectedCategory == null
      ? _service.getArticles()
      : _service.getArticlesByCategory(_selectedCategory!);

  void _selectCategory(String? category) {
    setState(() => _selectedCategory = category);
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    final categories = _service.getCategories();

    return Scaffold(
      appBar: AppBar(
        title: Text(_selectedCategory ?? 'MiniPress'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            DrawerHeader(
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.inversePrimary,
              ),
              child: const Text(
                'Catégories',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
            ),
            ListTile(
              title: const Text('Tous les articles'),
              selected: _selectedCategory == null,
              onTap: () => _selectCategory(null),
            ),
            const Divider(),
            ...categories.map(
              (cat) => ListTile(
                title: Text(cat),
                selected: _selectedCategory == cat,
                onTap: () => _selectCategory(cat),
              ),
            ),
          ],
        ),
      ),
      body: _articles.isEmpty
          ? const Center(child: Text('Aucun article dans cette catégorie.'))
          : ListView.builder(
              itemCount: _articles.length,
              itemBuilder: (context, index) {
                final Article article = _articles[index];
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
