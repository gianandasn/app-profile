import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/localization/app_localizations.dart';
import '../../core/localization/locale_provider.dart';
import '../../core/theme/app_theme.dart';
import '../../models/page_model.dart';
import '../../services/portal_service.dart';
import '../widgets/app_footer.dart';
import '../widgets/app_header.dart';

class StaticPageScreen extends StatefulWidget {
  final String slug;

  const StaticPageScreen({super.key, required this.slug});

  @override
  State<StaticPageScreen> createState() => _StaticPageScreenState();
}

class _StaticPageScreenState extends State<StaticPageScreen> {
  bool _isLoading = true;
  String? _error;
  PageModel? _page;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _loadPage();
  }

  @override
  void didUpdateWidget(covariant StaticPageScreen oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.slug != widget.slug) {
      _loadPage();
    }
  }

  Future<void> _loadPage() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final service = context.read<PortalService>();
    try {
      final page = await service.getPageDetail(widget.slug);
      if (mounted) {
        setState(() {
          _page = page;
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
                        onPressed: _loadPage,
                        child: Text(context.tr('btn_retry')),
                      ),
                    ],
                  ),
                )
              : _page == null
                  ? Center(child: Text(context.tr('no_data')))
                  : SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          Center(
                            child: ConstrainedBox(
                              constraints: const BoxConstraints(maxWidth: 900),
                              child: Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 36),
                                child: Card(
                                  child: Padding(
                                    padding: const EdgeInsets.all(32),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          _page!.title,
                                          style: const TextStyle(
                                            fontSize: 28,
                                            fontWeight: FontWeight.bold,
                                            color: AppTheme.textDark,
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        const Divider(),
                                        const SizedBox(height: 20),
                                        if (_page!.image != null && _page!.image!.isNotEmpty) ...[
                                          ClipRRect(
                                            borderRadius: BorderRadius.circular(12),
                                            child: Image.network(
                                              _page!.image!,
                                              width: double.infinity,
                                              height: 300,
                                              fit: BoxFit.cover,
                                              errorBuilder: (context, error, stackTrace) => const SizedBox.shrink(),
                                            ),
                                          ),
                                          const SizedBox(height: 24),
                                        ],
                                        Text(
                                          _stripHtml(_page!.content),
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
                              ),
                            ),
                          ),
                          const SizedBox(height: 48),
                          const AppFooter(),
                        ],
                      ),
                    ),
    );
  }
}
