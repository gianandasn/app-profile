import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/localization/app_localizations.dart';
import '../../core/localization/locale_provider.dart';
import '../../core/theme/app_theme.dart';
import '../../models/post.dart';
import '../../services/portal_service.dart';
import '../widgets/app_footer.dart';
import '../widgets/app_header.dart';

class PostDetailScreen extends StatefulWidget {
  final String slug;

  const PostDetailScreen({super.key, required this.slug});

  @override
  State<PostDetailScreen> createState() => _PostDetailScreenState();
}

class _PostDetailScreenState extends State<PostDetailScreen> {
  bool _isLoading = true;
  String? _error;
  PostModel? _post;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _loadPost();
  }

  Future<void> _loadPost() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final service = context.read<PortalService>();
    try {
      final post = await service.getPostDetail(widget.slug);
      if (mounted) {
        setState(() {
          _post = post;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  // Clean HTML tags for simple rich text rendering
  String _stripHtml(String htmlString) {
    return htmlString
        .replaceAll(RegExp(r'<[^>]*>|&[^;]+;'), ' ')
        .replaceAll(RegExp(r'\s+'), ' ')
        .trim();
  }

  @override
  Widget build(BuildContext context) {
    context.watch<LocaleProvider>();

    return Scaffold(
      appBar: const AppHeader(),
      drawer: const AppDrawer(),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.error_outline, size: 48, color: Colors.red),
                      const SizedBox(height: 12),
                      Text(context.tr('error_generic')),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: _loadPost,
                        child: Text(context.tr('btn_retry')),
                      ),
                    ],
                  ),
                )
              : _post == null
                  ? Center(child: Text(context.tr('no_data')))
                  : SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          _buildContentHeader(context, _post!),
                          const SizedBox(height: 48),
                          const AppFooter(),
                        ],
                      ),
                    ),
    );
  }

  Widget _buildContentHeader(BuildContext context, PostModel post) {
    return Center(
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 860),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextButton.icon(
                onPressed: () => context.go('/posts'),
                icon: const Icon(Icons.arrow_back, size: 16),
                label: Text(context.tr('nav_news')),
                style: TextButton.styleFrom(padding: EdgeInsets.zero),
              ),
              const SizedBox(height: 16),
              if (post.category != null)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  margin: const EdgeInsets.only(bottom: 12),
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(6),
                  ),
                  child: Text(
                    post.category!.name,
                    style: const TextStyle(
                      color: AppTheme.primaryColor,
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              Text(
                post.title,
                style: const TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.textDark,
                  height: 1.3,
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  const Icon(Icons.calendar_today, size: 14, color: AppTheme.textMuted),
                  const SizedBox(width: 6),
                  Text(
                    post.publishedAt != null ? post.publishedAt!.split(' ').first : '',
                    style: const TextStyle(fontSize: 13, color: AppTheme.textMuted),
                  ),
                  const SizedBox(width: 16),
                  const Icon(Icons.visibility_outlined, size: 16, color: AppTheme.textMuted),
                  const SizedBox(width: 6),
                  Text(
                    '${post.viewCount} ${context.tr('views')}',
                    style: const TextStyle(fontSize: 13, color: AppTheme.textMuted),
                  ),
                  if (post.author != null) ...[
                    const SizedBox(width: 16),
                    const Icon(Icons.person_outline, size: 16, color: AppTheme.textMuted),
                    const SizedBox(width: 6),
                    Text(
                      '${context.tr('author')}: ${post.author}',
                      style: const TextStyle(fontSize: 13, color: AppTheme.textMuted),
                    ),
                  ],
                ],
              ),
              const SizedBox(height: 24),
              if (post.image != null && post.image!.isNotEmpty)
                ClipRRect(
                  borderRadius: BorderRadius.circular(16),
                  child: Image.network(
                    post.image!,
                    width: double.infinity,
                    height: 380,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) => const SizedBox.shrink(),
                  ),
                ),
              const SizedBox(height: 24),
              if (post.summary != null && post.summary!.isNotEmpty)
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.blue.shade50,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: Colors.blue.shade100),
                  ),
                  child: Text(
                    post.summary!,
                    style: TextStyle(
                      fontSize: 15,
                      fontStyle: FontStyle.italic,
                      color: Colors.blue.shade900,
                      height: 1.5,
                    ),
                  ),
                ),
              const SizedBox(height: 24),
              // Body content
              Text(
                _stripHtml(post.content),
                style: const TextStyle(
                  fontSize: 16,
                  color: Color(0xFF334155),
                  height: 1.8,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
