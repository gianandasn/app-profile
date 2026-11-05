import '../core/api/api_endpoints.dart';
import 'category.dart';

class PostModel {
  final int id;
  final String title;
  final String slug;
  final String? summary;
  final String content;
  final String? image;
  final String status;
  final String? publishedAt;
  final int viewCount;
  final bool isFeatured;
  final CategoryModel? category;
  final String? author;
  final String? createdAt;

  PostModel({
    required this.id,
    required this.title,
    required this.slug,
    this.summary,
    required this.content,
    this.image,
    required this.status,
    this.publishedAt,
    this.viewCount = 0,
    this.isFeatured = false,
    this.category,
    this.author,
    this.createdAt,
  });

  factory PostModel.fromJson(Map<String, dynamic> json) {
    return PostModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      summary: json['summary'] as String?,
      content: json['content'] as String? ?? '',
      image: ApiEndpoints.resolveImageUrl(json['image'] as String?),
      status: json['status'] as String? ?? 'published',
      publishedAt: json['published_at'] as String?,
      viewCount: json['view_count'] as int? ?? 0,
      isFeatured: json['is_featured'] as bool? ?? false,
      category: json['category'] != null && json['category'] is Map<String, dynamic>
          ? CategoryModel.fromJson(json['category'] as Map<String, dynamic>)
          : null,
      author: json['author'] as String?,
      createdAt: json['created_at'] as String?,
    );
  }
}

