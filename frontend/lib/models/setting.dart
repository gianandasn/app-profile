class SettingModel {
  final String key;
  final dynamic value;
  final String type;
  final String group;

  SettingModel({
    required this.key,
    required this.value,
    required this.type,
    required this.group,
  });

  factory SettingModel.fromJson(Map<String, dynamic> json) {
    return SettingModel(
      key: json['key'] as String? ?? '',
      value: json['value'],
      type: json['type'] as String? ?? 'text',
      group: json['group'] as String? ?? 'general',
    );
  }

  String get asString => value?.toString() ?? '';
}

