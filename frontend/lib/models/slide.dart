class SlideModel {
  final int id;
  final String title;
  final String? description;
  final String image;
  final String? linkUrl;
  final int sortOrder;

  SlideModel({
    required this.id,
    required this.title,
    this.description,
    required this.image,
    this.linkUrl,
    this.sortOrder = 0,
  });

  factory SlideModel.fromJson(Map<String, dynamic> json) {
    return SlideModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      description: json['description'] as String?,
      image: json['image'] as String? ?? '',
      linkUrl: json['link_url'] as String?,
      sortOrder: json['sort_order'] as int? ?? 0,
    );
  }
}

