import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../views/faqs/faq_screen.dart';
import '../../views/home/home_screen.dart';
import '../../views/pages/static_page_screen.dart';
import '../../views/posts/post_detail_screen.dart';
import '../../views/posts/post_list_screen.dart';
import '../../views/questions/ask_question_screen.dart';
import '../../views/questions/question_list_screen.dart';

class AppRouter {
  static final GoRouter router = GoRouter(
    initialLocation: '/',
    errorBuilder: (context, state) => Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 64, color: Colors.red),
            const SizedBox(height: 16),
            Text(
              '404 - Halaman Tidak Ditemukan: ${state.uri}',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.go('/'),
              child: const Text('Kembali ke Beranda'),
            ),
          ],
        ),
      ),
    ),
    routes: [
      GoRoute(
        path: '/',
        name: 'home',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: '/posts',
        name: 'posts',
        builder: (context, state) {
          final category = state.uri.queryParameters['category'];
          return PostListScreen(initialCategory: category);
        },
        routes: [
          GoRoute(
            path: ':slug',
            name: 'post-detail',
            builder: (context, state) {
              final slug = state.pathParameters['slug'] ?? '';
              return PostDetailScreen(slug: slug);
            },
          ),
        ],
      ),
      GoRoute(
        path: '/pages/:slug',
        name: 'static-page',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return StaticPageScreen(slug: slug);
        },
      ),
      GoRoute(
        path: '/questions',
        name: 'questions',
        builder: (context, state) => const QuestionListScreen(),
        routes: [
          GoRoute(
            path: 'ask',
            name: 'ask-question',
            builder: (context, state) => const AskQuestionScreen(),
          ),
        ],
      ),
      GoRoute(
        path: '/faqs',
        name: 'faqs',
        builder: (context, state) => const FaqScreen(),
      ),
    ],
  );
}

