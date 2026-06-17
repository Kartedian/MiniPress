import 'package:dio/dio.dart';
import '../models/article.dart';
import '../models/article_preview.dart';
import '../models/category.dart';

class ApiClient {
  static const String _baseUrl = 'http://docketu.iutnc.univ-lorraine.fr:13887/api';

  final Dio _dio = Dio(
    BaseOptions(
      baseUrl: _baseUrl,
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {'Content-Type': 'application/json'},
    ),
  );

  Future<List<ArticlePreview>> getArticles() async {
    final response = await _dio.get('/articles');
    return (response.data as List)
        .map((json) => ArticlePreview.fromJson(json as Map<String, dynamic>))
        .toList();
  }

  Future<Article> getArticleById(String id) async {
    final response = await _dio.get('/articles/$id');
    return Article.fromJson(response.data as Map<String, dynamic>);
  }

  Future<List<Category>> getCategories() async {
    final response = await _dio.get('/categories');
    return (response.data as List)
        .map((json) => Category.fromJson(json as Map<String, dynamic>))
        .toList();
  }

  Future<List<ArticlePreview>> getArticlesByCategory(int categoryId) async {
    final response = await _dio.get('/categories/$categoryId/articles');
    return (response.data as List)
        .map((json) => ArticlePreview.fromJson(json as Map<String, dynamic>))
        .toList();
  }

  Future<List<ArticlePreview>> getArticlesByAuthor(String authorId) async {
    final response = await _dio.get('/auteurs/$authorId/articles');
    return (response.data as List)
        .map((json) => ArticlePreview.fromJson(json as Map<String, dynamic>))
        .toList();
  }
}
