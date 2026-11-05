import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/localization/app_localizations.dart';
import '../../core/localization/locale_provider.dart';
import '../../core/theme/app_theme.dart';
import '../../models/faq.dart';
import '../../services/portal_service.dart';
import '../widgets/app_footer.dart';
import '../widgets/app_header.dart';

class FaqScreen extends StatefulWidget {
  const FaqScreen({super.key});

  @override
  State<FaqScreen> createState() => _FaqScreenState();
}

class _FaqScreenState extends State<FaqScreen> {
  bool _isLoading = true;
  String? _error;
  List<FaqModel> _allFaqs = [];
  List<FaqModel> _filteredFaqs = [];
  final TextEditingController _searchController = TextEditingController();

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _loadFaqs();
  }

  Future<void> _loadFaqs() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final service = context.read<PortalService>();
    try {
      final faqs = await service.getFaqs();
      if (mounted) {
        setState(() {
          _allFaqs = faqs;
          _applyFilter();
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

  void _applyFilter() {
    final query = _searchController.text.trim().toLowerCase();
    if (query.isEmpty) {
      _filteredFaqs = List.from(_allFaqs);
    } else {
      _filteredFaqs = _allFaqs.where((f) {
        return f.question.toLowerCase().contains(query) ||
            f.answer.toLowerCase().contains(query) ||
            (f.topic != null && f.topic!.name.toLowerCase().contains(query));
      }).toList();
    }
  }

  String _stripHtml(String htmlString) {
    return htmlString
        .replaceAll(RegExp(r'<[^>]*>|&[^;]+;'), ' ')
        .replaceAll(RegExp(r'\s+'), ' ')
        .trim();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    context.watch<LocaleProvider>();

    return Scaffold(
      appBar: const AppHeader(),
      drawer: const AppDrawer(),
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHeader(context),
            const SizedBox(height: 24),
            _buildFaqList(context),
            const SizedBox(height: 48),
            const AppFooter(),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      width: double.infinity,
      color: Colors.white,
      padding: const EdgeInsets.symmetric(vertical: 28, horizontal: 20),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 900),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                context.tr('faq_title'),
                style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppTheme.textDark),
              ),
              const SizedBox(height: 6),
              Text(
                context.tr('faq_subtitle'),
                style: const TextStyle(color: AppTheme.textMuted, fontSize: 14),
              ),
              const SizedBox(height: 20),
              TextField(
                controller: _searchController,
                decoration: InputDecoration(
                  hintText: context.tr('search_articles'),
                  prefixIcon: const Icon(Icons.search),
                  suffixIcon: _searchController.text.isNotEmpty
                      ? IconButton(
                          icon: const Icon(Icons.clear),
                          onPressed: () {
                            _searchController.clear();
                            setState(_applyFilter);
                          },
                        )
                      : null,
                ),
                onChanged: (_) => setState(_applyFilter),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFaqList(BuildContext context) {
    if (_isLoading) {
      return const Padding(
        padding: EdgeInsets.all(48),
        child: Center(child: CircularProgressIndicator()),
      );
    }

    if (_error != null) {
      return Padding(
        padding: const EdgeInsets.all(48),
        child: Center(
          child: Column(
            children: [
              const Icon(Icons.error_outline, size: 48, color: Colors.red),
              const SizedBox(height: 12),
              Text(context.tr('error_generic')),
              const SizedBox(height: 12),
              ElevatedButton(
                onPressed: _loadFaqs,
                child: Text(context.tr('btn_retry')),
              ),
            ],
          ),
        ),
      );
    }

    if (_filteredFaqs.isEmpty) {
      return Padding(
        padding: const EdgeInsets.all(64),
        child: Center(
          child: Column(
            children: [
              const Icon(Icons.help_outline, size: 64, color: Colors.grey),
              const SizedBox(height: 16),
              Text(
                context.tr('no_data'),
                style: const TextStyle(fontSize: 16, color: AppTheme.textMuted),
              ),
            ],
          ),
        ),
      );
    }

    return Center(
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 900),
        child: ListView.separated(
          itemCount: _filteredFaqs.length,
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 20),
          separatorBuilder: (_, _) => const SizedBox(height: 12),
          itemBuilder: (context, index) {
            final faq = _filteredFaqs[index];
            return Card(
              clipBehavior: Clip.antiAlias,
              child: ExpansionTile(
                tilePadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                childrenPadding: const EdgeInsets.fromLTRB(20, 0, 20, 20),
                leading: CircleAvatar(
                  radius: 18,
                  backgroundColor: AppTheme.primaryColor.withValues(alpha: 0.08),
                  child: const Text('Q', style: TextStyle(color: AppTheme.primaryColor, fontWeight: FontWeight.bold)),
                ),
                title: Text(
                  faq.question,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textDark,
                  ),
                ),
                subtitle: faq.topic != null
                    ? Padding(
                        padding: const EdgeInsets.only(top: 4),
                        child: Text(
                          faq.topic!.name,
                          style: const TextStyle(fontSize: 12, color: AppTheme.primaryColor),
                        ),
                      )
                    : null,
                children: [
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF8FAFC),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(
                      _stripHtml(faq.answer),
                      style: const TextStyle(
                        fontSize: 14,
                        color: Color(0xFF334155),
                        height: 1.6,
                      ),
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
