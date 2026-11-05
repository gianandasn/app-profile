import 'package:flutter/material.dart';

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations) ??
        AppLocalizations(const Locale('id'));
  }

  static const _localizedValues = <String, Map<String, String>>{
    'id': {
      'app_title': 'Portal Profil & Pelayanan Publik',
      'nav_home': 'Beranda',
      'nav_news': 'Berita & Artikel',
      'nav_profile': 'Profil',
      'nav_vision': 'Visi & Misi',
      'nav_questions': 'Pertanyaan Warga',
      'nav_ask': 'Kirim Pertanyaan',
      'nav_faq': 'FAQ',
      'hero_welcome': 'Selamat Datang di Portal Pelayanan Publik',
      'hero_subtitle':
          'Akses berita, pengumuman, dan sampaikan pertanyaan Anda dengan mudah dan transparan.',
      'latest_news': 'Kabar & Berita Terbaru',
      'view_all': 'Lihat Semua',
      'search_articles': 'Cari berita atau artikel...',
      'all_categories': 'Semua Kategori',
      'published_on': 'Diterbitkan',
      'author': 'Penulis',
      'views': 'dilihat',
      'read_more': 'Baca Selengkapnya',
      'no_data': 'Tidak ada data ditemukan.',
      'quick_links': 'Layanan & Navigasi Cepat',
      'cta_title': 'Punya Pertanyaan Seputar Layanan?',
      'cta_subtitle':
          'Sampaikan pertanyaan, masukan, atau saran langsung kepada petugas kami.',
      'cta_button': 'Buat Pertanyaan Sekarang',
      'faq_title': 'Pertanyaan yang Sering Diajukan (FAQ)',
      'faq_subtitle': 'Temukan jawaban cepat atas pertanyaan umum seputar layanan.',
      'question_list_title': 'Arsip Pertanyaan & Jawaban Publik',
      'question_list_subtitle':
          'Daftar pertanyaan dari masyarakat yang telah dijawab oleh instansi.',
      'ask_form_title': 'Formulir Pertanyaan Masyarakat',
      'ask_form_subtitle':
          'Isi formulir di bawah ini dengan lengkap agar kami dapat merespons dengan cepat.',
      'field_topic': 'Topik Layanan',
      'field_topic_hint': 'Pilih topik yang sesuai',
      'field_name': 'Nama Lengkap',
      'field_name_hint': 'Masukkan nama Anda',
      'field_email': 'Alamat Email',
      'field_email_hint': 'contoh@email.com',
      'field_phone': 'Nomor WhatsApp / Telepon',
      'field_phone_hint': '081234567890 (opsional)',
      'field_subject': 'Subjek Pertanyaan',
      'field_subject_hint': 'Ringkasan pertanyaan Anda',
      'field_body': 'Uraian Pertanyaan',
      'field_body_hint': 'Jelaskan pertanyaan atau kebutuhan Anda secara detail...',
      'btn_submit': 'Kirim Pertanyaan',
      'btn_submitting': 'Mengirim...',
      'success_submit':
          'Pertanyaan Anda berhasil dikirim! Petugas kami akan segera meninjau dan merespons.',
      'error_required': 'Kolom ini wajib diisi.',
      'error_email': 'Masukkan format email yang valid.',
      'error_generic': 'Terjadi kendala saat memuat data.',
      'btn_retry': 'Muat Ulang',
      'contact_us': 'Hubungi Kami',
      'office_address': 'Alamat Kantor',
      'footer_rights': 'Hak Cipta Dilindungi.',
      'status_answered': 'Dijawab',
      'status_pending': 'Menunggu Jawaban',
      'response_official': 'Tanggapan Resmi Instansi',
    },
    'en': {
      'app_title': 'Profile & Public Service Portal',
      'nav_home': 'Home',
      'nav_news': 'News & Articles',
      'nav_profile': 'Profile',
      'nav_vision': 'Vision & Mission',
      'nav_questions': 'Citizen Inquiries',
      'nav_ask': 'Ask a Question',
      'nav_faq': 'FAQ',
      'hero_welcome': 'Welcome to the Public Service Portal',
      'hero_subtitle':
          'Access news, official announcements, and submit your inquiries easily and transparently.',
      'latest_news': 'Latest News & Updates',
      'view_all': 'View All',
      'search_articles': 'Search news or articles...',
      'all_categories': 'All Categories',
      'published_on': 'Published on',
      'author': 'Author',
      'views': 'views',
      'read_more': 'Read More',
      'no_data': 'No data found.',
      'quick_links': 'Quick Links & Services',
      'cta_title': 'Have Questions About Our Services?',
      'cta_subtitle':
          'Submit your questions, feedback, or suggestions directly to our team.',
      'cta_button': 'Ask a Question Now',
      'faq_title': 'Frequently Asked Questions (FAQ)',
      'faq_subtitle': 'Find quick answers to common questions regarding our services.',
      'question_list_title': 'Public Questions & Answers Archive',
      'question_list_subtitle':
          'Questions submitted by citizens that have been answered by the institution.',
      'ask_form_title': 'Citizen Inquiry Form',
      'ask_form_subtitle':
          'Please complete the form below so we can respond promptly.',
      'field_topic': 'Service Topic',
      'field_topic_hint': 'Select an appropriate topic',
      'field_name': 'Full Name',
      'field_name_hint': 'Enter your full name',
      'field_email': 'Email Address',
      'field_email_hint': 'example@email.com',
      'field_phone': 'WhatsApp / Phone Number',
      'field_phone_hint': '+6281234567890 (optional)',
      'field_subject': 'Subject / Title',
      'field_subject_hint': 'Brief summary of your inquiry',
      'field_body': 'Inquiry Details',
      'field_body_hint': 'Explain your question or request in detail...',
      'btn_submit': 'Submit Question',
      'btn_submitting': 'Submitting...',
      'success_submit':
          'Your question was submitted successfully! Our staff will review and respond shortly.',
      'error_required': 'This field is required.',
      'error_email': 'Please enter a valid email address.',
      'error_generic': 'Failed to load data.',
      'btn_retry': 'Retry',
      'contact_us': 'Contact Us',
      'office_address': 'Office Address',
      'footer_rights': 'All Rights Reserved.',
      'status_answered': 'Answered',
      'status_pending': 'Pending Response',
      'response_official': 'Official Institutional Response',
    },
  };

  String translate(String key) {
    final langCode = locale.languageCode;
    return _localizedValues[langCode]?[key] ??
        _localizedValues['id']?[key] ??
        key;
  }
}

class AppLocalizationsDelegate extends LocalizationsDelegate<AppLocalizations> {
  const AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) => ['id', 'en'].contains(locale.languageCode);

  @override
  Future<AppLocalizations> load(Locale locale) async {
    return AppLocalizations(locale);
  }

  @override
  bool shouldReload(AppLocalizationsDelegate old) => false;
}

extension TranslationHelper on BuildContext {
  String tr(String key) => AppLocalizations.of(this).translate(key);
}

