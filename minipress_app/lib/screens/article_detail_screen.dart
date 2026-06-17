import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import '../models/article.dart';
import '../models/category.dart';
import '../services/api_client.dart';
import 'author_articles_screen.dart';

class ArticleDetailScreen extends StatefulWidget {
  final String articleId;
  final String titre;

  const ArticleDetailScreen(
      {super.key, required this.articleId, required this.titre});

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  final _client = ApiClient();
  Article? _article;
  List<Category> _categories = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadDetail();
  }

  Future<void> _loadDetail() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final articleF = _client.getArticleById(widget.articleId);
      final categoriesF = _client.getCategories();
      final article = await articleF;
      final categories = await categoriesF;
      setState(() {
        _article = article;
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

  String _formatDate(DateTime date) {
    return '${date.day.toString().padLeft(2, '0')}/'
        '${date.month.toString().padLeft(2, '0')}/'
        '${date.year}';
  }

  String? get _categoryName {
    final article = _article;
    if (article == null || article.categorie == null) return null;
    final match = _categories.where((c) => c.id == article.categorie);
    return match.isEmpty ? null : match.first.libelle;
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final metaStyle =
        theme.textTheme.bodySmall?.copyWith(color: Colors.grey[600]);
    final authorStyle = metaStyle?.copyWith(
      color: theme.colorScheme.primary,
      decoration: TextDecoration.underline,
    );

    return Scaffold(
      appBar: AppBar(
        title: Text(widget.titre),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
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
                        onPressed: _loadDetail,
                        child: const Text('Réessayer'),
                      ),
                    ],
                  ),
                )
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _article!.titre,
                        style: theme.textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 8),
                      Text.rich(
                        TextSpan(
                          style: metaStyle,
                          children: [
                            TextSpan(text: '${_formatDate(_article!.createdAt)} · '),
                            TextSpan(
                              text: _article!.auteur.name,
                              style: authorStyle,
                              recognizer: TapGestureRecognizer()
                                ..onTap = () => Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (_) => AuthorArticlesScreen(
                                          authorId: _article!.auteur.id,
                                          authorName: _article!.auteur.name,
                                        ),
                                      ),
                                    ),
                            ),
                            if (_categoryName != null) TextSpan(text: ' · $_categoryName'),
                          ],
                        ),
                      ),
                      const Divider(height: 24),

                      if (_article!.urlImage != null) ...[
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.network(
                            _article!.urlImage!,
                            width: double.infinity,
                            height: 220,
                            fit: BoxFit.cover,
                            errorBuilder: (_, __, ___) => Container(
                              width: double.infinity,
                              height: 120,
                              color: Colors.grey[200],
                              child: const Icon(Icons.broken_image, size: 40, color: Colors.grey),
                            ),
                          ),
                        ),
                        const SizedBox(height: 16), // Espacement sous l'image
                      ],

                      if (_article!.contenue != null)
                        Text(
                          _article!.contenue!,
                          style: theme.textTheme.bodyMedium,
                        ),
                    ],
                  ),
                ),
    );
  }
}
