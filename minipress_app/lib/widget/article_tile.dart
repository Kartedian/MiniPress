import 'package:flutter/material.dart';
import '../models/article_preview.dart';

class ArticleTile extends StatelessWidget {
  final ArticlePreview article;
  final VoidCallback onTap;

  const ArticleTile({super.key, required this.article, required this.onTap});

  String _formatDate(DateTime date) {
    return '${date.day.toString().padLeft(2, '0')}/'
        '${date.month.toString().padLeft(2, '0')}/'
        '${date.year}';
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      child: ListTile(
        onTap: onTap,
        leading: article.urlImage != null
            ? ClipRRect(
                borderRadius: BorderRadius.circular(6),
                child: Image.network(
                  article.urlImage!,
                  width: 56,
                  height: 56,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) =>
                      const Icon(Icons.image_not_supported, size: 40),
                ),
              )
            : null,
        title: Text(
          article.titre,
          style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
        ),
        subtitle: Text(
          '${_formatDate(article.createdAt)} · ${article.auteur.name}',
          style: theme.textTheme.bodySmall,
        ),
        trailing: const Icon(Icons.chevron_right),
      ),
    );
  }
}
