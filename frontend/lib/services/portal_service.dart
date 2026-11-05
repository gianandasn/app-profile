import '../core/api/api_client.dart';
import '../core/api/api_endpoints.dart';
import '../models/category.dart';
import '../models/faq.dart';
import '../models/menu.dart';
import '../models/page_model.dart';
import '../models/post.dart';
import '../models/question.dart';
import '../models/question_topic.dart';
import '../models/setting.dart';
import '../models/slide.dart';

class PortalService {
  final ApiClient _apiClient;

  PortalService(this._apiClient);

  // Slides
  Future<List<SlideModel>> getSlides() async {
    final res = await _apiClient.get(ApiEndpoints.slides);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => SlideModel.fromJson(json as Map<String, dynamic>)).toList();
  }

  // Posts
  Future<List<PostModel>> getPosts({
    String? category,
    bool? featured,
    String? search,
    int page = 1,
    int perPage = 10,
  }) async {
    final queryParams = <String, dynamic>{
      'page': page,
      'per_page': perPage,
    };
    if (category != null && category.isNotEmpty) {
      queryParams['category'] = category;
    }
    if (featured != null) {
      queryParams['featured'] = featured;
    }
    if (search != null && search.isNotEmpty) {
      queryParams['search'] = search;
    }

    final res = await _apiClient.get(ApiEndpoints.posts, queryParameters: queryParams);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => PostModel.fromJson(json as Map<String, dynamic>)).toList();
  }

  // Single Post
  Future<PostModel> getPostDetail(String slug) async {
    final res = await _apiClient.get('${ApiEndpoints.posts}/$slug');
    return PostModel.fromJson(res['data'] as Map<String, dynamic>);
  }

  // Single Page
  Future<PageModel> getPageDetail(String slug) async {
    final res = await _apiClient.get('${ApiEndpoints.pages}/$slug');
    return PageModel.fromJson(res['data'] as Map<String, dynamic>);
  }

  // Categories
  Future<List<CategoryModel>> getCategories() async {
    final res = await _apiClient.get(ApiEndpoints.categories);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => CategoryModel.fromJson(json as Map<String, dynamic>)).toList();
  }

  // Menu by location ('header', 'footer', 'sidebar')
  Future<MenuModel?> getMenu(String location) async {
    try {
      final res = await _apiClient.get('${ApiEndpoints.menus}/$location');
      if (res['data'] != null) {
        return MenuModel.fromJson(res['data'] as Map<String, dynamic>);
      }
    } catch (_) {}
    return null;
  }

  // Settings
  Future<Map<String, String>> getSettings({String? group}) async {
    final queryParams = group != null ? {'group': group} : null;
    final res = await _apiClient.get(ApiEndpoints.settings, queryParameters: queryParams);
    final data = res['data'] as List<dynamic>? ?? [];
    final map = <String, String>{};
    for (final item in data) {
      final setting = SettingModel.fromJson(item as Map<String, dynamic>);
      map[setting.key] = setting.asString;
    }
    return map;
  }

  // Question Topics
  Future<List<QuestionTopicModel>> getQuestionTopics() async {
    final res = await _apiClient.get(ApiEndpoints.questionTopics);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => QuestionTopicModel.fromJson(json as Map<String, dynamic>)).toList();
  }

  // Questions (Answered & Published)
  Future<List<QuestionModel>> getQuestions({String? topic, int page = 1, int perPage = 10}) async {
    final queryParams = <String, dynamic>{
      'page': page,
      'per_page': perPage,
    };
    if (topic != null && topic.isNotEmpty) {
      queryParams['topic'] = topic;
    }

    final res = await _apiClient.get(ApiEndpoints.questions, queryParameters: queryParams);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => QuestionModel.fromJson(json as Map<String, dynamic>)).toList();
  }

  // Submit Question
  Future<bool> submitQuestion({
    required int topicId,
    required String name,
    required String email,
    String? phone,
    required String subject,
    required String body,
    required String locale,
  }) async {
    await _apiClient.post(
      ApiEndpoints.questions,
      body: {
        'topic_id': topicId,
        'name': name,
        'email': email,
        'phone': phone,
        'subject': subject,
        'body': body,
        'locale': locale,
      },
    );
    return true;
  }

  // FAQs
  Future<List<FaqModel>> getFaqs() async {
    final res = await _apiClient.get(ApiEndpoints.faqs);
    final data = res['data'] as List<dynamic>? ?? [];
    return data.map((json) => FaqModel.fromJson(json as Map<String, dynamic>)).toList();
  }
}

