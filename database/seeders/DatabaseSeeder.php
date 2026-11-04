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

        // 2. Kategori Berita/Artikel
        $berita = Category::firstOrCreate(
            ['slug' => 'berita-utama'],
            [
                'name' => 'Berita Utama',
                'description' => 'Berita dan kabar terkini seputar kegiatan instansi',
                'icon' => 'heroicon-o-newspaper',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $pengumuman = Category::firstOrCreate(
            ['slug' => 'pengumuman'],
            [
                'name' => 'Pengumuman',
                'description' => 'Pengumuman resmi untuk masyarakat umum',
                'icon' => 'heroicon-o-megaphone',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 3. Halaman Statis
        $pageProfil = Page::firstOrCreate(
            ['slug' => 'profil-instansi'],
            [
                'user_id' => $admin->id,
                'title' => 'Profil Instansi',
                'content' => '<h2>Tentang Kami</h2><p>Selamat datang di portal resmi instansi kami. Kami berdedikasi untuk memberikan pelayanan terbaik dan transparan kepada seluruh lapisan masyarakat.</p>',
                'status' => 'published',
                'sort_order' => 1,
                'show_in_menu' => true,
            ]
        );

        $pageVisiMisi = Page::firstOrCreate(
            ['slug' => 'visi-dan-misi'],
            [
                'user_id' => $admin->id,
                'title' => 'Visi dan Misi',
                'content' => '<h2>Visi</h2><p>Menjadi instansi terdepan dalam pelayanan publik yang modern, akuntabel, dan berorientasi pada kepuasan masyarakat.</p><h2>Misi</h2><ol><li>Mewujudkan pelayanan prima yang responsif dan inklusif.</li><li>Mendorong inovasi berbasis teknologi digital.</li><li>Meningkatkan partisipasi aktif masyarakat.</li></ol>',
                'status' => 'published',
                'sort_order' => 2,
                'show_in_menu' => true,
            ]
        );

        // 4. Sample Post
        Post::firstOrCreate(
            ['slug' => 'peluncuran-portal-layanan-digital-terbaru'],
            [
                'user_id' => $admin->id,
                'category_id' => $berita->id,
                'title' => 'Peluncuran Portal Layanan Digital Terbaru',
                'summary' => 'Inovasi digital terbaru diluncurkan untuk mempermudah masyarakat mengakses informasi dan mengajukan pertanyaan secara langsung.',
                'content' => '<p>Hari ini secara resmi kami meluncurkan platform digital baru yang terintegrasi dengan aplikasi mobile dan web. Platform ini memudahkan masyarakat untuk berkonsultasi, bertanya, dan memperoleh informasi terkini secara mudah dan cepat.</p>',
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 125,
                'is_featured' => true,
            ]
        );

        // 5. Topik Pertanyaan
        $topicLayanan = QuestionTopic::firstOrCreate(
            ['slug' => 'layanan-publik'],
            [
                'name' => 'Layanan Publik',
                'description' => 'Pertanyaan seputar prosedur, jadwal, dan syarat layanan',
                'icon' => 'heroicon-o-briefcase',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $topicPengaduan = QuestionTopic::firstOrCreate(
            ['slug' => 'saran-pengaduan'],
            [
                'name' => 'Saran & Pengaduan',
                'description' => 'Kritik, saran, dan pengaduan masyarakat',
                'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 6. Sample FAQ
        Faq::firstOrCreate(
            ['question' => 'Bagaimana cara mengajukan pertanyaan kepada instansi?'],
            [
                'topic_id' => $topicLayanan->id,
                'answer' => '<p>Anda dapat mengajukan pertanyaan melalui menu "Kirim Pertanyaan" pada aplikasi mobile Flutter kami atau melalui formulir kontak yang tersedia. Petugas kami akan segera merespons pertanyaan Anda.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'Berapa lama waktu yang dibutuhkan untuk mendapatkan jawaban?'],
            [
                'topic_id' => $topicLayanan->id,
                'answer' => '<p>Pertanyaan yang masuk biasanya akan dijawab dalam waktu 1-2 hari kerja sesuai jam operasional.</p>',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 7. Pengaturan Situs (Settings)
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Portal Profil & Pelayanan Publik', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Melayani Masyarakat dengan Integritas dan Transparansi', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'kontak@instansi.go.id', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(024) 1234567', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'office_address', 'value' => 'Jl. Pahlawan No. 12, Semarang, Jawa Tengah', 'type' => 'textarea'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => 'https://instagram.com/instansi', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_youtube', 'value' => 'https://youtube.com/@instansi', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // 8. Menu Navigasi
        $menuHeader = Menu::firstOrCreate(
            ['name' => 'Menu Utama Header'],
            [
                'location' => 'header',
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Beranda'],
            [
                'url' => '/',
                'type' => 'custom',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Profil'],
            [
                'type' => 'page',
                'linkable_id' => $pageProfil->id,
                'linkable_type' => Page::class,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menuHeader->id, 'label' => 'Visi & Misi'],
            [
                'type' => 'page',
                'linkable_id' => $pageVisiMisi->id,
                'linkable_type' => Page::class,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // 9. Sample Banner Slide
        Slide::firstOrCreate(
            ['title' => 'Selamat Datang di Portal Pelayanan'],
            [
                'description' => 'Akses informasi terkini dan sampaikan pertanyaan Anda dengan mudah melalui aplikasi.',
                'image' => 'slides/default-banner.jpg',
                'link_url' => '/profil-instansi',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );
    }
}
