import 'question_topic.dart';

class FaqModel {
  final int id;
  final String question;
  final String answer;
  final int sortOrder;
  final QuestionTopicModel? topic;

  FaqModel({
    required this.id,
    required this.question,
    required this.answer,
    this.sortOrder = 0,
    this.topic,
  });

  factory FaqModel.fromJson(Map<String, dynamic> json) {
    return FaqModel(
      id: json['id'] as int? ?? 0,
      question: json['question'] as String? ?? '',
      answer: json['answer'] as String? ?? '',
      sortOrder: json['sort_order'] as int? ?? 0,
      topic: json['topic'] != null && json['topic'] is Map<String, dynamic>
          ? QuestionTopicModel.fromJson(json['topic'] as Map<String, dynamic>)
          : null,
    );
  }
}

