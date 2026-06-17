class Auteur {
  final String id;
  final String? userId;
  final String name;

  const Auteur({required this.id, this.userId, required this.name});

  factory Auteur.fromJson(Map<String, dynamic> json) {
    return Auteur(
      id: (json['id'] ?? '').toString(),
      userId: json['user_id'] as String?,
      name: (json['name'] ?? '').toString(),
    );
  }
}
