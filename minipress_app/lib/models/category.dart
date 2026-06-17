class Category {
  final int id;
  final String libelle;
  final String? description;

  const Category({required this.id, required this.libelle, this.description});

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'] as int,
      libelle: json['libelle'] as String,
      description: json['description'] as String?,
    );
  }
}
