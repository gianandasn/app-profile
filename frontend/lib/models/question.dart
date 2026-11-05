import 'question_topic.dart';

class QuestionAnswerModel {
  final int id;
  final String body;
  final String? answeredBy;
  final String? answeredAt;

  QuestionAnswerModel({
    required this.id,
    required this.body,
    this.answeredBy,
    this.answeredAt,
  });

  factory QuestionAnswerModel.fromJson(Map<String, dynamic> json) {
    return QuestionAnswerModel(
      id: json['id'] as int? ?? 0,
      body: json['body'] as String? ?? '',
      answeredBy: json['answered_by'] as String?,
      answeredAt: json['answered_at'] as String?,
    );
  }
}

class QuestionModel {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String subject;
  final String body;
  final String status;
  final String? locale;
  final String? createdAt;
  final QuestionTopicModel? topic;
  final List<QuestionAnswerModel> answers;

  QuestionModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.subject,
    required this.body,
    required this.status,
    this.locale,
    this.createdAt,
    this.topic,
    this.answers = const [],
  });

  factory QuestionModel.fromJson(Map<String, dynamic> json) {
    return QuestionModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      phone: json['phone'] as String?,
      subject: json['subject'] as String? ?? '',
      body: json['body'] as String? ?? '',
      status: json['status'] as String? ?? 'pending',
      locale: json['locale'] as String?,
      createdAt: json['created_at'] as String?,
      topic: json['topic'] != null && json['topic'] is Map<String, dynamic>
          ? QuestionTopicModel.fromJson(json['topic'] as Map<String, dynamic>)
          : null,
      answers: (json['answers'] as List<dynamic>?)
              ?.map((a) => QuestionAnswerModel.fromJson(a as Map<String, dynamic>))
              .toList() ??
          [],
    );
  }
}

