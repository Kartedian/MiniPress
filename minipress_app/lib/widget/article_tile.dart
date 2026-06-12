import 'package:flutter/material.dart';
import '../models/article.dart';

class ArticleTile extends StatelessWidget {
  final Article article;
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
        title: Text(
          article.title,
          style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
        ),
        subtitle: Text(
          '${_formatDate(article.createdAt)} · ${article.author}',
          style: theme.textTheme.bodySmall,
        ),
        trailing: const Icon(Icons.chevron_right),
      ),
    );
  }
}
