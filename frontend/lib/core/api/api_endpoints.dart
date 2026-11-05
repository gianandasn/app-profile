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

  /// Helper to resolve media URLs to work across Web, Desktop, and Android Emulator
  static String? resolveImageUrl(String? url) {
    if (url == null || url.isEmpty) return null;
    if (url.startsWith('/')) {
      final baseUri = Uri.parse(baseUrl);
      final hostPort = baseUri.hasPort ? '${baseUri.host}:${baseUri.port}' : baseUri.host;
      return '${baseUri.scheme}://$hostPort$url';
    }
    if (kIsWeb) {
      if (Uri.base.host.isNotEmpty && Uri.base.host != 'localhost' && Uri.base.host != '127.0.0.1') {
        return url
            .replaceFirst('localhost:8000', '${Uri.base.host}:8000')
            .replaceFirst('127.0.0.1:8000', '${Uri.base.host}:8000');
      }
    } else if (defaultTargetPlatform == TargetPlatform.android) {
      return url
          .replaceFirst('http://localhost:8000', 'http://10.0.2.2:8000')
          .replaceFirst('http://127.0.0.1:8000', 'http://10.0.2.2:8000');
    }
    return url;
  }
}

