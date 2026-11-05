import 'package:flutter/foundation.dart';

class ApiEndpoints {
  // Configurable base URL with environment variable support & platform detection
  static String get baseUrl {
    const envUrl = String.fromEnvironment('API_URL');
    if (envUrl.isNotEmpty) return envUrl;

    if (!kIsWeb && defaultTargetPlatform == TargetPlatform.android) {
      return 'http://10.0.2.2:8000/api/v1';
    }
    return 'http://127.0.0.1:8000/api/v1';
  }

  // CMS Endpoints
  static const String posts = '/posts';
  static const String pages = '/pages';
  static const String categories = '/categories';
  static const String slides = '/slides';
  static const String menus = '/menus';
  static const String settings = '/settings';

  // Citizen Questions & FAQ
  static const String questionTopics = '/question-topics';
  static const String questions = '/questions';
  static const String faqs = '/faqs';
}

