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
    return Article(
      id: json['id'] as String,
      titre: json['titre'] as String,
      resumer: json['resumer'] as String?,
      contenue: json['contenue'] as String?,
      date: json['date'] as String,
      categorie: json['categorie'] != null
          ? int.tryParse(json['categorie'].toString())
          : null,
      urlImage: json['url_image'] as String?,
      auteur: Auteur.fromJson(json['auteur'] as Map<String, dynamic>),
      published: json['published'] != null
          ? int.tryParse(json['published'].toString())
          : null,
    );
  }

  DateTime get createdAt => DateTime.parse(date);
}
