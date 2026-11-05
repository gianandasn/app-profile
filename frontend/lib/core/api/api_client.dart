import 'dart:convert';
import 'package:http/http.dart' as http;
import 'api_endpoints.dart';

class ApiClient {
  final http.Client _client;
  String currentLocale;

  ApiClient({http.Client? client, this.currentLocale = 'id'})
      : _client = client ?? http.Client();

  void updateLocale(String locale) {
    currentLocale = locale;
  }

  Map<String, String> _headers() {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Accept-Language': currentLocale,
    };
  }

  Uri _buildUri(String path, [Map<String, dynamic>? queryParameters]) {
    final baseUrl = ApiEndpoints.baseUrl;
    final urlString = '$baseUrl$path';
    final uri = Uri.parse(urlString);

    if (queryParameters != null && queryParameters.isNotEmpty) {
      final stringParams = queryParameters.map((key, value) => MapEntry(key, value.toString()));
      return uri.replace(queryParameters: {
        ...uri.queryParameters,
        ...stringParams,
      });
    }

    return uri;
  }

  Future<dynamic> get(String path, {Map<String, dynamic>? queryParameters}) async {
    final uri = _buildUri(path, queryParameters);
    try {
      final response = await _client.get(uri, headers: _headers());
      return _processResponse(response);
    } catch (e) {
      throw ApiException('Network or server error: $e');
    }
  }

  Future<dynamic> post(String path, {dynamic body}) async {
    final uri = _buildUri(path);
    try {
      final response = await _client.post(
        uri,
        headers: _headers(),
        body: body != null ? jsonEncode(body) : null,
      );
      return _processResponse(response);
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Network or server error: $e');
    }
  }

  dynamic _processResponse(http.Response response) {
    final utf8Body = utf8.decode(response.bodyBytes);
    final dynamic jsonBody = utf8Body.isNotEmpty ? jsonDecode(utf8Body) : null;

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonBody;
    }

    if (response.statusCode == 422) {
      final message = jsonBody is Map && jsonBody['message'] != null
          ? jsonBody['message'].toString()
          : 'Validation error';
      final errors = jsonBody is Map && jsonBody['errors'] is Map
          ? Map<String, dynamic>.from(jsonBody['errors'])
          : null;
      throw ApiValidationException(message, errors: errors);
    }

    final message = jsonBody is Map && jsonBody['message'] != null
        ? jsonBody['message'].toString()
        : 'Request failed with status: ${response.statusCode}';
    throw ApiException(message, statusCode: response.statusCode);
  }
}

class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => message;
}

class ApiValidationException extends ApiException {
  final Map<String, dynamic>? errors;

  ApiValidationException(super.message, {this.errors, super.statusCode = 422});

  String get firstErrorMessage {
    if (errors != null && errors!.isNotEmpty) {
      final firstVal = errors!.values.first;
      if (firstVal is List && firstVal.isNotEmpty) {
        return firstVal.first.toString();
      }
      return firstVal.toString();
    }
    return message;
  }
}

