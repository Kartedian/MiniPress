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
    final rawUrlImage = json['url_image'] as String?;
    return ArticlePreview(
      titre: (json['titre'] ?? '').toString(),
      date: (json['date'] ?? '').toString(),
      auteur: Auteur.fromJson(json['auteur'] as Map<String, dynamic>),
      url: (json['url'] ?? '').toString(),
      urlImage: rawUrlImage != null && rawUrlImage.isNotEmpty
          ? 'http://docketu.iutnc.univ-lorraine.fr:13887$rawUrlImage'
          : null,
    );
  }

  String get id => url.split('/').where((s) => s.isNotEmpty).last;
  DateTime get createdAt => DateTime.tryParse(date) ?? DateTime(0);
}
