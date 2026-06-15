import 'package:flutter/material.dart';
import '../models/article_preview.dart';
import '../models/category.dart';
import '../services/api_client.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class ArticleListScreen extends StatefulWidget {
  const ArticleListScreen({super.key});

  @override
  State<ArticleListScreen> createState() => _ArticleListScreenState();
}

class _ArticleListScreenState extends State<ArticleListScreen> {
  final _client = ApiClient();
  final _searchController = TextEditingController();

  List<ArticlePreview> _allArticles = [];
  List<Category> _categories = [];
  bool _isLoading = true;
  String? _error;
  Category? _selectedCategory;
  String _searchQuery = '';
  bool _isSearching = false;
  bool _sortAscending = false;

  @override
  void initState() {
    super.initState();
    _loadInitialData();
  }

  Future<void> _loadInitialData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final articlesF = _client.getArticles();
      final categoriesF = _client.getCategories();
      final articles = await articlesF;
      final categories = await categoriesF;
      setState(() {
        _allArticles = articles;
        _categories = categories;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  Future<void> _loadArticlesForCategory(Category? category) async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final articles = category == null
          ? await _client.getArticles()
          : await _client.getArticlesByCategory(category.id);
      setState(() {
        _allArticles = articles;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  List<ArticlePreview> get _filteredArticles {
    List<ArticlePreview> result = List.from(_allArticles);

    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      result = result
          .where((a) =>
              a.titre.toLowerCase().contains(query) ||
              a.auteur.name.toLowerCase().contains(query))
          .toList();
    }

    result.sort((a, b) => _sortAscending
        ? a.createdAt.compareTo(b.createdAt)
        : b.createdAt.compareTo(a.createdAt));

    return result;
  }

  void _toggleSort() => setState(() => _sortAscending = !_sortAscending);

  void _selectCategory(Category? category) {
    setState(() => _selectedCategory = category);
    Navigator.pop(context);
    _loadArticlesForCategory(category);
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
    final articles = _filteredArticles;

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
            : Text(_selectedCategory?.libelle ?? 'MiniPress'),
        actions: [
          Tooltip(
            message: _sortAscending ? 'Décroissant' : 'Croissant',
            child: IconButton(
              icon: Icon(
                  _sortAscending ? Icons.arrow_downward : Icons.arrow_upward),
              onPressed: _toggleSort,
            ),
          ),
          if (_isSearching)
            IconButton(icon: const Icon(Icons.close), onPressed: _stopSearch)
          else
            IconButton(icon: const Icon(Icons.search), onPressed: _startSearch),
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
            ..._categories.map(
              (cat) => ListTile(
                title: Text(cat.libelle),
                selected: _selectedCategory?.id == cat.id,
                onTap: () => _selectCategory(cat),
              ),
            ),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text('Erreur : $_error'),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: _loadInitialData,
                        child: const Text('Réessayer'),
                      ),
                    ],
                  ),
                )
              : articles.isEmpty
                  ? const Center(child: Text('Aucun article trouvé.'))
                  : RefreshIndicator(
                      onRefresh: _loadInitialData,
                      child: ListView.builder(
                        itemCount: articles.length,
                        itemBuilder: (context, index) {
                          final article = articles[index];
                          return ArticleTile(
                            article: article,
                            onTap: () => Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => ArticleDetailScreen(
                                  articleId: article.id,
                                  titre: article.titre,
                                ),
                              ),
                            ),
                          );
                        },
                      ),
                    ),
    );
  }
}
