class MenuItemModel {
  final int id;
  final String label;
  final String? url;
  final String type;
  final int? linkableId;
  final String? linkableType;
  final int sortOrder;
  final List<MenuItemModel> children;

  MenuItemModel({
    required this.id,
    required this.label,
    this.url,
    required this.type,
    this.linkableId,
    this.linkableType,
    this.sortOrder = 0,
    this.children = const [],
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) {
    return MenuItemModel(
      id: json['id'] as int? ?? 0,
      label: json['label'] as String? ?? '',
      url: json['url'] as String?,
      type: json['type'] as String? ?? 'custom',
      linkableId: json['linkable_id'] as int?,
      linkableType: json['linkable_type'] as String?,
      sortOrder: json['sort_order'] as int? ?? 0,
      children: (json['children'] as List<dynamic>?)
              ?.map((item) => MenuItemModel.fromJson(item as Map<String, dynamic>))
              .toList() ??
          [],
    );
  }
}

class MenuModel {
  final int id;
  final String name;
  final String location;
  final List<MenuItemModel> items;

  MenuModel({
    required this.id,
    required this.name,
    required this.location,
    this.items = const [],
  });

  factory MenuModel.fromJson(Map<String, dynamic> json) {
    return MenuModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      location: json['location'] as String? ?? '',
      items: (json['items'] as List<dynamic>?)
              ?.map((item) => MenuItemModel.fromJson(item as Map<String, dynamic>))
              .toList() ??
          [],
    );
  }
}

