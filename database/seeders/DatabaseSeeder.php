<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Kategori Berita/Artikel (Bilingual)
        $berita = Category::create([
            'name' => [
                'id' => 'Berita Utama',
                'en' => 'Main News',
            ],
            'slug' => [
                'id' => 'berita-utama',
                'en' => 'main-news',
            ],
            'description' => [
                'id' => 'Berita dan kabar terkini seputar kegiatan instansi',
                'en' => 'Latest news and updates regarding institution activities',
            ],
            'icon' => 'heroicon-o-newspaper',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $pengumuman = Category::create([
            'name' => [
                'id' => 'Pengumuman',
                'en' => 'Announcements',
            ],
            'slug' => [
                'id' => 'pengumuman',
                'en' => 'announcements',
            ],
            'description' => [
                'id' => 'Pengumuman resmi untuk masyarakat umum',
                'en' => 'Official announcements for the general public',
            ],
            'icon' => 'heroicon-o-megaphone',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 3. Halaman Statis (Bilingual)
        $pageProfil = Page::create([
            'user_id' => $admin->id,
            'title' => [
                'id' => 'Profil Instansi',
                'en' => 'Institution Profile',
            ],
            'slug' => [
                'id' => 'profil-instansi',
                'en' => 'institution-profile',
            ],
            'content' => [
                'id' => '<h2>Tentang Kami</h2><p>Selamat datang di portal resmi instansi kami. Kami berdedikasi untuk memberikan pelayanan terbaik dan transparan kepada seluruh lapisan masyarakat.</p>',
                'en' => '<h2>About Us</h2><p>Welcome to our official institutional portal. We are dedicated to providing the best and most transparent services to the public.</p>',
            ],
            'status' => 'published',
            'sort_order' => 1,
            'show_in_menu' => true,
        ]);

        $pageVisiMisi = Page::create([
            'user_id' => $admin->id,
            'title' => [
                'id' => 'Visi dan Misi',
                'en' => 'Vision and Mission',
            ],
            'slug' => [
                'id' => 'visi-dan-misi',
                'en' => 'vision-and-mission',
            ],
            'content' => [
                'id' => '<h2>Visi</h2><p>Menjadi instansi terdepan dalam pelayanan publik yang modern, akuntabel, dan berorientasi pada kepuasan masyarakat.</p><h2>Misi</h2><ol><li>Mewujudkan pelayanan prima yang responsif dan inklusif.</li><li>Mendorong inovasi berbasis teknologi digital.</li><li>Meningkatkan partisipasi aktif masyarakat.</li></ol>',
                'en' => '<h2>Vision</h2><p>To be a leading institution in modern, accountable public services oriented toward public satisfaction.</p><h2>Mission</h2><ol><li>Deliver responsive and inclusive prime service.</li><li>Drive digital technology-based innovation.</li><li>Enhance active public participation.</li></ol>',
            ],
            'status' => 'published',
            'sort_order' => 2,
            'show_in_menu' => true,
        ]);

        // 4. Sample Post (Bilingual)
        Post::create([
            'user_id' => $admin->id,
            'category_id' => $berita->id,
            'title' => [
                'id' => 'Peluncuran Portal Layanan Digital Terbaru',
                'en' => 'Launch of the Latest Digital Service Portal',
            ],
            'slug' => [
                'id' => 'peluncuran-portal-layanan-digital-terbaru',
                'en' => 'launch-of-the-latest-digital-service-portal',
            ],
            'summary' => [
                'id' => 'Inovasi digital terbaru diluncurkan untuk mempermudah masyarakat mengakses informasi dan mengajukan pertanyaan secara langsung.',
                'en' => 'The latest digital innovation has been launched to make it easier for the public to access information and submit questions directly.',
            ],
            'content' => [
                'id' => '<p>Hari ini secara resmi kami meluncurkan platform digital baru yang terintegrasi dengan aplikasi mobile dan web. Platform ini memudahkan masyarakat untuk berkonsultasi, bertanya, dan memperoleh informasi terkini secara mudah dan cepat.</p>',
                'en' => '<p>Today we officially launched our new digital platform integrated with mobile and web applications. This platform makes it easier for citizens to consult, inquire, and obtain up-to-date information quickly and easily.</p>',
            ],
            'status' => 'published',
            'published_at' => now(),
            'view_count' => 125,
            'is_featured' => true,
        ]);

        // 5. Topik Pertanyaan (Bilingual)
        $topicLayanan = QuestionTopic::create([
            'name' => [
                'id' => 'Layanan Publik',
                'en' => 'Public Services',
            ],
            'slug' => [
                'id' => 'layanan-publik',
                'en' => 'public-services',
            ],
            'description' => [
                'id' => 'Pertanyaan seputar prosedur, jadwal, dan syarat layanan',
                'en' => 'Questions regarding procedures, schedules, and service requirements',
            ],
            'icon' => 'heroicon-o-briefcase',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $topicPengaduan = QuestionTopic::create([
            'name' => [
                'id' => 'Saran & Pengaduan',
                'en' => 'Suggestions & Complaints',
            ],
            'slug' => [
                'id' => 'saran-pengaduan',
                'en' => 'suggestions-and-complaints',
            ],
            'description' => [
                'id' => 'Kritik, saran, dan pengaduan masyarakat',
                'en' => 'Public criticism, suggestions, and complaints',
            ],
            'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 6. Sample FAQ (Bilingual)
        Faq::create([
            'topic_id' => $topicLayanan->id,
            'question' => [
                'id' => 'Bagaimana cara mengajukan pertanyaan kepada instansi?',
                'en' => 'How can I submit a question to the institution?',
            ],
            'answer' => [
                'id' => '<p>Anda dapat mengajukan pertanyaan melalui menu "Kirim Pertanyaan" pada aplikasi mobile Flutter kami atau melalui formulir kontak yang tersedia. Petugas kami akan segera merespons pertanyaan Anda.</p>',
                'en' => '<p>You can submit a question via the "Send Question" menu on our Flutter mobile app or via the available contact form. Our team will respond promptly.</p>',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'topic_id' => $topicLayanan->id,
            'question' => [
                'id' => 'Berapa lama waktu yang dibutuhkan untuk mendapatkan jawaban?',
                'en' => 'How long does it take to get a response?',
            ],
            'answer' => [
                'id' => '<p>Pertanyaan yang masuk biasanya akan dijawab dalam waktu 1-2 hari kerja sesuai jam operasional.</p>',
                'en' => '<p>Incoming inquiries are usually answered within 1-2 business days during operational hours.</p>',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 7. Pengaturan Situs (Settings - Bilingual Values)
        $settings = [
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => [
                    'id' => 'Portal Profil & Pelayanan Publik',
                    'en' => 'Profile & Public Service Portal',
                ],
                'type' => 'text',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => [
                    'id' => 'Melayani Masyarakat dengan Integritas dan Transparansi',
                    'en' => 'Serving the Community with Integrity and Transparency',
                ],
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'contact_email',
                'value' => 'kontak@instansi.go.id',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'contact_phone',
                'value' => '(024) 1234567',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'office_address',
                'value' => [
                    'id' => 'Jl. Pahlawan No. 12, Semarang, Jawa Tengah',
                    'en' => '12 Pahlawan Street, Semarang, Central Java',
                ],
                'type' => 'textarea',
            ],
            [
                'group' => 'social',
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/instansi',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@instansi',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // 8. Menu Navigasi (Bilingual)
        $menuHeader = Menu::create([
            'name' => [
                'id' => 'Menu Utama Header',
                'en' => 'Main Header Menu',
            ],
            'location' => 'header',
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'label' => [
                'id' => 'Beranda',
                'en' => 'Home',
            ],
            'url' => '/',
            'type' => 'custom',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'label' => [
                'id' => 'Profil',
                'en' => 'Profile',
            ],
            'type' => 'page',
            'linkable_id' => $pageProfil->id,
            'linkable_type' => Page::class,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menuHeader->id,
            'label' => [
                'id' => 'Visi & Misi',
                'en' => 'Vision & Mission',
            ],
            'type' => 'page',
            'linkable_id' => $pageVisiMisi->id,
            'linkable_type' => Page::class,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 9. Sample Banner Slide (Bilingual)
        Slide::create([
            'title' => [
                'id' => 'Selamat Datang di Portal Pelayanan',
                'en' => 'Welcome to the Service Portal',
            ],
            'description' => [
                'id' => 'Akses informasi terkini dan sampaikan pertanyaan Anda dengan mudah melalui aplikasi.',
                'en' => 'Access the latest information and submit your inquiries easily through the application.',
            ],
            'image' => 'slides/default-banner.jpg',
            'link_url' => '/profil-instansi',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
