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
  final _searchController = TextEditingController();
  String? _selectedCategory;
  String _searchQuery = '';
  bool _isSearching = false;
  bool _sortAscending = false;

  List<Article> get _articles {
    final base = _selectedCategory == null
        ? _service.getArticles()
        : _service.getArticlesByCategory(_selectedCategory!);

    List<Article> result = base;

    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      result = result
          .where((a) =>
              a.title.toLowerCase().contains(query) ||
              a.author.toLowerCase().contains(query) ||
              a.content.toLowerCase().contains(query))
          .toList();
    }

    if (_sortAscending) {
      result = List.from(result)
        ..sort((a, b) => a.createdAt.compareTo(b.createdAt));
    }

    return result;
  }

  void _toggleSort() => setState(() => _sortAscending = !_sortAscending);

  void _selectCategory(String? category) {
    setState(() => _selectedCategory = category);
    Navigator.pop(context);
  }

  void _startSearch() => setState(() => _isSearching = true);

  void _stopSearch() {
    setState(() {
      _isSearching = false;
      _searchQuery = '';
      _searchController.clear();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final categories = _service.getCategories();

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: _isSearching
            ? TextField(
                controller: _searchController,
                autofocus: true,
                decoration: const InputDecoration(
                  hintText: 'Rechercher...',
                  border: InputBorder.none,
                ),
                onChanged: (value) => setState(() => _searchQuery = value),
              )
            : Text(_selectedCategory ?? 'MiniPress'),
        actions: [
          Tooltip(
            message: _sortAscending ? 'Décroissant' : 'Croissant',
            child: IconButton(
              icon: Icon(_sortAscending ? Icons.arrow_upward : Icons.arrow_downward),
              onPressed: _toggleSort,
            ),
          ),
          if (_isSearching)
            IconButton(
              icon: const Icon(Icons.close),
              onPressed: _stopSearch,
            )
          else
            IconButton(
              icon: const Icon(Icons.search),
              onPressed: _startSearch,
            ),
        ],
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
          ? const Center(child: Text('Aucun article trouvé.'))
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
