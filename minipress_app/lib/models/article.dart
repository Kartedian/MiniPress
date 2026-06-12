class Article {
  final int id;
  final String title;
  final String content;
  final String author;
  final DateTime createdAt;
  final String category;

  const Article({
    required this.id,
    required this.title,
    required this.content,
    required this.author,
    required this.createdAt,
    required this.category,
  });
}
