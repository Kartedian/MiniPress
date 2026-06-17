import 'auteur.dart';

class ArticlePreview {
  final String titre;
  final String date;
  final Auteur auteur;
  final String url;
  final String? urlImage;

  const ArticlePreview({
    required this.titre,
    required this.date,
    required this.auteur,
    required this.url,
    this.urlImage,
  });

  factory ArticlePreview.fromJson(Map<String, dynamic> json) {
    return ArticlePreview(
      titre: json['titre'] as String,
      date: json['date'] as String,
      auteur: Auteur.fromJson(json['auteur'] as Map<String, dynamic>),
      url: json['url'] as String,
      urlImage: json['url_image'] as String?,
    );
  }

  String get id => url.split('/').where((s) => s.isNotEmpty).last;
  DateTime get createdAt => DateTime.parse(date);
}
