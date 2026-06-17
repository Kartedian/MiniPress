import 'auteur.dart';

class Article {
  final String id;
  final String titre;
  final String? resumer;
  final String? contenue;
  final String date;
  final int? categorie;
  final String? urlImage;
  final Auteur auteur;
  final int? published;

  const Article({
    required this.id,
    required this.titre,
    this.resumer,
    this.contenue,
    required this.date,
    this.categorie,
    this.urlImage,
    required this.auteur,
    this.published,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    final rawUrlImage = json['url_image'] as String?;
    return Article(
      id: (json['id'] ?? '').toString(),
      titre: (json['titre'] ?? '').toString(),
      resumer: json['resumer'] as String?,
      contenue: json['contenue'] as String?,
      date: (json['date'] ?? '').toString(),
      categorie: json['categorie'] != null
          ? int.tryParse(json['categorie'].toString())
          : null,
      // 📸 Reconstitution automatique de l'URL absolue
      urlImage: rawUrlImage != null && rawUrlImage.isNotEmpty
          ? 'http://docketu.iutnc.univ-lorraine.fr:13887$rawUrlImage'
          : null,
      auteur: Auteur.fromJson(json['auteur'] as Map<String, dynamic>),
      published: json['published'] != null
          ? int.tryParse(json['published'].toString())
          : null,
    );
  }

  DateTime get createdAt => DateTime.tryParse(date) ?? DateTime(0);
}
