import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/localization/app_localizations.dart';
import '../../core/localization/locale_provider.dart';
import '../../core/theme/app_theme.dart';

class AppHeader extends StatelessWidget implements PreferredSizeWidget {
  final String? title;

  const AppHeader({super.key, this.title});

  @override
  Size get preferredSize => const Size.fromHeight(70);

  @override
  Widget build(BuildContext context) {
    final localeProvider = context.watch<LocaleProvider>();
    final currentLang = localeProvider.languageCode;
    final isDesktop = MediaQuery.of(context).size.width > 900;

    return AppBar(
      titleSpacing: 20,
      title: InkWell(
        onTap: () => context.go('/'),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(Icons.account_balance, color: AppTheme.primaryColor, size: 28),
            ),
            const SizedBox(width: 12),
            Flexible(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    title ?? context.tr('app_title'),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textDark,
                    ),
                  ),
                  Text(
                    context.tr('nav_home'),
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.textMuted,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      actions: [
        if (isDesktop) ...[
          _navButton(context, label: context.tr('nav_home'), path: '/'),
          _navButton(context, label: context.tr('nav_news'), path: '/posts'),
          _navButton(context, label: context.tr('nav_profile'), path: '/pages/profil-instansi'),
          _navButton(context, label: context.tr('nav_vision'), path: '/pages/visi-dan-misi'),
          _navButton(context, label: context.tr('nav_questions'), path: '/questions'),
          _navButton(context, label: context.tr('nav_faq'), path: '/faqs'),
          const SizedBox(width: 8),
          ElevatedButton.icon(
            onPressed: () => context.go('/questions/ask'),
            icon: const Icon(Icons.send_rounded, size: 16),
            label: Text(context.tr('nav_ask')),
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              textStyle: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(width: 12),
        ],
        // Language Switcher Dropdown / Toggle
        Container(
          margin: const EdgeInsets.symmetric(vertical: 14),
          padding: const EdgeInsets.symmetric(horizontal: 10),
          decoration: BoxDecoration(
            color: Colors.grey.shade100,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: Colors.grey.shade300),
          ),
          child: DropdownButtonHideUnderline(
            child: DropdownButton<String>(
              value: currentLang,
              icon: const Icon(Icons.language, size: 18, color: AppTheme.primaryColor),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.bold,
                color: AppTheme.textDark,
              ),
              onChanged: (newLang) {
                if (newLang != null) {
                  localeProvider.setLocale(newLang);
                }
              },
              items: const [
                DropdownMenuItem(
                  value: 'id',
                  child: Text('🇮🇩 ID'),
                ),
                DropdownMenuItem(
                  value: 'en',
                  child: Text('🇬🇧 EN'),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(width: 16),
      ],
    );
  }

  Widget _navButton(BuildContext context, {required String label, required String path}) {
    return TextButton(
      onPressed: () => context.go(path),
      style: TextButton.styleFrom(
        foregroundColor: AppTheme.textDark,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      ),
      child: Text(
        label,
        style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
      ),
    );
  }
}

class AppDrawer extends StatelessWidget {
  const AppDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: const BoxDecoration(color: AppTheme.primaryColor),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                const Icon(Icons.account_balance, color: Colors.white, size: 40),
                const SizedBox(height: 12),
                Text(
                  context.tr('app_title'),
                  style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.home_outlined),
            title: Text(context.tr('nav_home')),
            onTap: () {
              Navigator.pop(context);
              context.go('/');
            },
          ),
          ListTile(
            leading: const Icon(Icons.newspaper_outlined),
            title: Text(context.tr('nav_news')),
            onTap: () {
              Navigator.pop(context);
              context.go('/posts');
            },
          ),
          ListTile(
            leading: const Icon(Icons.info_outline),
            title: Text(context.tr('nav_profile')),
            onTap: () {
              Navigator.pop(context);
              context.go('/pages/profil-instansi');
            },
          ),
          ListTile(
            leading: const Icon(Icons.flag_outlined),
            title: Text(context.tr('nav_vision')),
            onTap: () {
              Navigator.pop(context);
              context.go('/pages/visi-dan-misi');
            },
          ),
          ListTile(
            leading: const Icon(Icons.question_answer_outlined),
            title: Text(context.tr('nav_questions')),
            onTap: () {
              Navigator.pop(context);
              context.go('/questions');
            },
          ),
          ListTile(
            leading: const Icon(Icons.help_outline),
            title: Text(context.tr('nav_faq')),
            onTap: () {
              Navigator.pop(context);
              context.go('/faqs');
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.send_rounded, color: AppTheme.primaryColor),
            title: Text(
              context.tr('nav_ask'),
              style: const TextStyle(color: AppTheme.primaryColor, fontWeight: FontWeight.bold),
            ),
            onTap: () {
              Navigator.pop(context);
              context.go('/questions/ask');
            },
          ),
        ],
      ),
    );
  }
}
