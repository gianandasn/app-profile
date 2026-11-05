import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/localization/app_localizations.dart';
import '../../core/localization/locale_provider.dart';
import '../../core/theme/app_theme.dart';
import '../../models/question_topic.dart';
import '../../services/portal_service.dart';
import '../widgets/app_footer.dart';
import '../widgets/app_header.dart';

class AskQuestionScreen extends StatefulWidget {
  const AskQuestionScreen({super.key});

  @override
  State<AskQuestionScreen> createState() => _AskQuestionScreenState();
}

class _AskQuestionScreenState extends State<AskQuestionScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _subjectController = TextEditingController();
  final _bodyController = TextEditingController();

  int? _selectedTopicId;
  List<QuestionTopicModel> _topics = [];
  bool _isLoadingTopics = true;
  bool _isSubmitting = false;
  bool _isSuccess = false;
  String? _errorMessage;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _loadTopics();
  }

  Future<void> _loadTopics() async {
    final service = context.read<PortalService>();
    try {
      final topics = await service.getQuestionTopics();
      if (mounted) {
        setState(() {
          _topics = topics;
          if (_topics.isNotEmpty && _selectedTopicId == null) {
            _selectedTopicId = _topics.first.id;
          }
          _isLoadingTopics = false;
        });
      }
    } catch (_) {
      if (mounted) {
        setState(() {
          _isLoadingTopics = false;
        });
      }
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedTopicId == null) return;

    setState(() {
      _isSubmitting = true;
      _errorMessage = null;
    });

    final service = context.read<PortalService>();
    final locale = context.read<LocaleProvider>().languageCode;

    try {
      await service.submitQuestion(
        topicId: _selectedTopicId!,
        name: _nameController.text.trim(),
        email: _emailController.text.trim(),
        phone: _phoneController.text.trim().isNotEmpty ? _phoneController.text.trim() : null,
        subject: _subjectController.text.trim(),
        body: _bodyController.text.trim(),
        locale: locale,
      );

      if (mounted) {
        setState(() {
          _isSubmitting = false;
          _isSuccess = true;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isSubmitting = false;
          if (e is ApiValidationException) {
            _errorMessage = e.firstErrorMessage;
          } else {
            _errorMessage = e.toString();
          }
        });
      }
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _subjectController.dispose();
    _bodyController.dispose();
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
            Center(
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 800),
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 36),
                  child: Card(
                    child: Padding(
                      padding: const EdgeInsets.all(32),
                      child: _isSuccess ? _buildSuccessView(context) : _buildFormView(context),
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

  Widget _buildSuccessView(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        const CircleAvatar(
          radius: 40,
          backgroundColor: Color(0xFFDCFCE7),
          child: Icon(Icons.check_circle, color: Color(0xFF16A34A), size: 48),
        ),
        const SizedBox(height: 20),
        Text(
          context.tr('nav_ask'),
          style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppTheme.textDark),
        ),
        const SizedBox(height: 12),
        Text(
          context.tr('success_submit'),
          textAlign: TextAlign.center,
          style: const TextStyle(fontSize: 15, color: Color(0xFF475569), height: 1.5),
        ),
        const SizedBox(height: 28),
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            OutlinedButton(
              onPressed: () {
                setState(() {
                  _isSuccess = false;
                  _nameController.clear();
                  _emailController.clear();
                  _phoneController.clear();
                  _subjectController.clear();
                  _bodyController.clear();
                });
              },
              child: Text(context.tr('nav_ask')),
            ),
            const SizedBox(width: 16),
            ElevatedButton(
              onPressed: () => context.go('/'),
              child: Text(context.tr('nav_home')),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildFormView(BuildContext context) {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            context.tr('ask_form_title'),
            style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppTheme.textDark),
          ),
          const SizedBox(height: 6),
          Text(
            context.tr('ask_form_subtitle'),
            style: const TextStyle(color: AppTheme.textMuted, fontSize: 14),
          ),
          const SizedBox(height: 24),
          if (_errorMessage != null)
            Container(
              padding: const EdgeInsets.all(12),
              margin: const EdgeInsets.only(bottom: 20),
              decoration: BoxDecoration(
                color: Colors.red.shade50,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(color: Colors.red.shade200),
              ),
              child: Row(
                children: [
                  const Icon(Icons.error_outline, color: Colors.red, size: 20),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      _errorMessage!,
                      style: const TextStyle(color: Colors.red, fontSize: 13),
                    ),
                  ),
                ],
              ),
            ),
          // Topic Field
          Text(
            context.tr('field_topic'),
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
          ),
          const SizedBox(height: 6),
          _isLoadingTopics
              ? const LinearProgressIndicator()
              : DropdownButtonFormField<int>(
                  initialValue: _selectedTopicId,
                  decoration: InputDecoration(hintText: context.tr('field_topic_hint')),
                  items: _topics.map((t) {
                    return DropdownMenuItem<int>(
                      value: t.id,
                      child: Text(t.name),
                    );
                  }).toList(),
                  onChanged: (val) {
                    setState(() {
                      _selectedTopicId = val;
                    });
                  },
                  validator: (val) => val == null ? context.tr('error_required') : null,
                ),
          const SizedBox(height: 18),
          // Name Field
          Text(
            context.tr('field_name'),
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
          ),
          const SizedBox(height: 6),
          TextFormField(
            controller: _nameController,
            decoration: InputDecoration(hintText: context.tr('field_name_hint')),
            validator: (val) => (val == null || val.trim().isEmpty) ? context.tr('error_required') : null,
          ),
          const SizedBox(height: 18),
          // Email & Phone
          Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      context.tr('field_email'),
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
                    ),
                    const SizedBox(height: 6),
                    TextFormField(
                      controller: _emailController,
                      keyboardType: TextInputType.emailAddress,
                      decoration: InputDecoration(hintText: context.tr('field_email_hint')),
                      validator: (val) {
                        if (val == null || val.trim().isEmpty) {
                          return context.tr('error_required');
                        }
                        if (!val.contains('@') || !val.contains('.')) {
                          return context.tr('error_email');
                        }
                        return null;
                      },
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      context.tr('field_phone'),
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
                    ),
                    const SizedBox(height: 6),
                    TextFormField(
                      controller: _phoneController,
                      keyboardType: TextInputType.phone,
                      decoration: InputDecoration(hintText: context.tr('field_phone_hint')),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 18),
          // Subject
          Text(
            context.tr('field_subject'),
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
          ),
          const SizedBox(height: 6),
          TextFormField(
            controller: _subjectController,
            decoration: InputDecoration(hintText: context.tr('field_subject_hint')),
            validator: (val) => (val == null || val.trim().isEmpty) ? context.tr('error_required') : null,
          ),
          const SizedBox(height: 18),
          // Body
          Text(
            context.tr('field_body'),
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: AppTheme.textDark),
          ),
          const SizedBox(height: 6),
          TextFormField(
            controller: _bodyController,
            maxLines: 5,
            decoration: InputDecoration(hintText: context.tr('field_body_hint')),
            validator: (val) => (val == null || val.trim().isEmpty) ? context.tr('error_required') : null,
          ),
          const SizedBox(height: 28),
          // Submit Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: _isSubmitting ? null : _submitForm,
              icon: _isSubmitting
                  ? const SizedBox(
                      width: 18,
                      height: 18,
                      child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                    )
                  : const Icon(Icons.send_rounded),
              label: Text(_isSubmitting ? context.tr('btn_submitting') : context.tr('btn_submit')),
            ),
          ),
        ],
      ),
    );
  }
}
