import '../core/api/api_endpoints.dart';

class PageModel {
  final int id;
  final String title;
  final String slug;
  final String content;
  final String? image;
  final String status;
  final int sortOrder;
  final bool showInMenu;

  PageModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.content,
    this.image,
    required this.status,
    this.sortOrder = 0,
    this.showInMenu = false,
  });

  factory PageModel.fromJson(Map<String, dynamic> json) {
    return PageModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      content: json['content'] as String? ?? '',
      image: ApiEndpoints.resolveImageUrl(json['image'] as String?),
      status: json['status'] as String? ?? 'published',
      sortOrder: json['sort_order'] as int? ?? 0,
      showInMenu: json['show_in_menu'] as bool? ?? false,
    );
  }
}

