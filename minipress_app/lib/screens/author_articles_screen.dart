import 'package:flutter/material.dart';
import '../models/article_preview.dart';
import '../services/api_client.dart';
import '../widget/article_tile.dart';
import 'article_detail_screen.dart';

class AuthorArticlesScreen extends StatefulWidget {
  final String authorId;
  final String authorName;

  const AuthorArticlesScreen(
      {super.key, required this.authorId, required this.authorName});

  @override
  State<AuthorArticlesScreen> createState() => _AuthorArticlesScreenState();
}

class _AuthorArticlesScreenState extends State<AuthorArticlesScreen> {
  final _client = ApiClient();
  List<ArticlePreview> _articles = [];
  bool _isLoading = true;
  String? _error;
  bool _sortAscending = false;

  @override
  void initState() {
    super.initState();
    _loadArticles();
  }

  Future<void> _loadArticles() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final articles = await _client.getArticlesByAuthor(widget.authorId);
      setState(() {
        _articles = articles;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  List<ArticlePreview> get _sortedArticles {
    final sorted = List<ArticlePreview>.from(_articles);
    sorted.sort((a, b) => _sortAscending
        ? a.createdAt.compareTo(b.createdAt)
        : b.createdAt.compareTo(a.createdAt));
    return sorted;
  }

  void _toggleSort() => setState(() => _sortAscending = !_sortAscending);

  @override
  Widget build(BuildContext context) {
    final articles = _sortedArticles;

    return Scaffold(
      appBar: AppBar(
        title: Text(widget.authorName),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        actions: [
          Tooltip(
            message: _sortAscending
                ? 'Plus ancien en premier'
                : 'Plus récent en premier',
            child: IconButton(
              icon: Icon(
                  _sortAscending ? Icons.arrow_upward : Icons.arrow_downward),
              onPressed: _toggleSort,
            ),
          ),
        ],
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
                        onPressed: _loadArticles,
                        child: const Text('Réessayer'),
                      ),
                    ],
                  ),
                )
              : articles.isEmpty
                  ? const Center(child: Text('Aucun article pour cet auteur.'))
                  : ListView.builder(
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
    );
  }
}
