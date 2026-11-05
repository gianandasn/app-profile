import 'package:flutter/material.dart';
import '../../core/localization/app_localizations.dart';

class AppFooter extends StatelessWidget {
  final Map<String, String>? settings;

  const AppFooter({super.key, this.settings});

  @override
  Widget build(BuildContext context) {
    final siteName = settings?['site_name'] ?? context.tr('app_title');
    final siteTagline = settings?['site_tagline'] ?? '';
    final address = settings?['office_address'] ?? 'Jl. Pahlawan No. 12, Semarang';
    final email = settings?['contact_email'] ?? 'kontak@instansi.go.id';
    final phone = settings?['contact_phone'] ?? '(024) 1234567';

    return Container(
      color: const Color(0xFF0F172A), // Dark slate
      padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 24),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 1100),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Wrap(
                spacing: 40,
                runSpacing: 24,
                children: [
                  SizedBox(
                    width: 320,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            const Icon(Icons.account_balance, color: Colors.white, size: 28),
                            const SizedBox(width: 10),
                            Expanded(
                              child: Text(
                                siteName,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ],
                        ),
                        if (siteTagline.isNotEmpty) ...[
                          const SizedBox(height: 10),
                          Text(
                            siteTagline,
                            style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
                          ),
                        ],
                      ],
                    ),
                  ),
                  SizedBox(
                    width: 300,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          context.tr('contact_us'),
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 12),
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Icon(Icons.location_on_outlined, color: Color(0xFF94A3B8), size: 18),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Text(
                                address,
                                style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const Icon(Icons.email_outlined, color: Color(0xFF94A3B8), size: 18),
                            const SizedBox(width: 8),
                            Text(
                              email,
                              style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const Icon(Icons.phone_outlined, color: Color(0xFF94A3B8), size: 18),
                            const SizedBox(width: 8),
                            Text(
                              phone,
                              style: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 32),
              const Divider(color: Color(0xFF334155)),
              const SizedBox(height: 16),
              Center(
                child: Text(
                  '© ${DateTime.now().year} $siteName. ${context.tr('footer_rights')}',
                  style: const TextStyle(color: Color(0xFF64748B), fontSize: 12),
                  textAlign: TextAlign.center,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
