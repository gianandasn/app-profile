import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LocaleProvider extends ChangeNotifier {
  static const String _localeKey = 'app_locale_code';

  Locale _locale = const Locale('id');

  Locale get locale => _locale;
  String get languageCode => _locale.languageCode;

  LocaleProvider() {
    _loadLocale();
  }

  Future<void> _loadLocale() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final code = prefs.getString(_localeKey);
      if (code != null && (code == 'id' || code == 'en')) {
        _locale = Locale(code);
        notifyListeners();
      }
    } catch (_) {
      // Default to Indonesian if error reading prefs
    }
  }

  Future<void> setLocale(String languageCode) async {
    if (languageCode != 'id' && languageCode != 'en') return;
    if (_locale.languageCode == languageCode) return;

    _locale = Locale(languageCode);
    notifyListeners();

    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_localeKey, languageCode);
    } catch (_) {}
  }

  void toggleLocale() {
    final next = _locale.languageCode == 'id' ? 'en' : 'id';
    setLocale(next);
  }
}

