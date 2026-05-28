<?php
$stmtReview = $pdo->prepare('
    SELECT rr.*, u.username as user_name, r.name as room_name 
    FROM room_reviews rr
    JOIN users u ON rr.user_id = u.id
    JOIN rooms r ON rr.room_id = r.id
    ORDER BY rr.id DESC 
    LIMIT 3
');
$stmtReview->execute();
$reviews = $stmtReview->fetchAll();
?>

<section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-emerald-100 min-h-[85vh] flex items-center">

    <!-- BACKGROUND DECOR -->
    <div class="absolute inset-0 overflow-hidden">

        <div class="absolute top-[-120px] right-[-120px] w-[320px] h-[320px] bg-emerald-200/40 rounded-full blur-3xl"></div>

        <div class="absolute bottom-[-150px] left-[-120px] w-[350px] h-[350px] bg-green-300/30 rounded-full blur-3xl"></div>

        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-brand-100/30 rounded-full blur-3xl"></div>

    </div>


    <!-- CONTENT -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 w-full">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- LEFT -->
            <div>

                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 bg-white border border-emerald-200 shadow-sm rounded-full px-5 py-2 mb-8">

                    <span class="w-2.5 h-2.5 rounded-full bg-brand animate-pulse"></span>

                    <span class="text-sm font-semibold text-brand">
                        Platform Pencarian Kos Modern
                    </span>

                </div>

                <!-- TITLE -->
                <h1 class="text-5xl md:text-6xl xl:text-7xl font-extrabold leading-tight text-gray-900">

                    Temukan Kos
                    <span class="text-brand">
                        Nyaman
                    </span>

                    <br>

                    Untuk Hidup
                    <span class="relative inline-block">

                        Lebih Mudah

                        <svg class="absolute -bottom-3 left-0 w-full" viewBox="0 0 200 12" fill="none">
                            <path d="M3 9C45 1 120 1 197 9" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                        </svg>

                    </span>

                </h1>

                <!-- DESC -->
                <p class="mt-8 text-lg leading-relaxed text-gray-600 max-w-2xl">

                    Cari kos berdasarkan lokasi, fasilitas, dan budget dengan tampilan modern,
                    proses cepat, dan pengalaman yang nyaman di semua perangkat.

                </p>

                <!-- BUTTON -->
                <div class="flex flex-wrap items-center gap-4 mt-10">

                    <a href="?page=catalog"
                       class="group inline-flex items-center gap-3 bg-brand hover:bg-emerald-600 text-white font-semibold px-8 py-4 rounded-2xl transition-all duration-300 shadow-xl shadow-emerald-200 hover:-translate-y-1">

                        <svg class="w-5 h-5 group-hover:rotate-6 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>

                        Cari Kos

                    </a>

                    <a href="?page=register"
                       class="inline-flex items-center gap-3 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 font-semibold px-8 py-4 rounded-2xl transition-all duration-300 shadow-sm hover:shadow-lg">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9a3 3 0 11-6 0 3 3 0 016 0zm-9 11a6 6 0 1112 0H9z"/>
                        </svg>

                        Buat Akun

                    </a>

                </div>

                <!-- STATS -->
                <div class="grid grid-cols-3 gap-6 mt-16 max-w-xl">

                    <div>
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            500+
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Kos tersedia
                        </p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            1K+
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Pengguna aktif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-extrabold text-gray-900">
                            24/7
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Support system
                        </p>
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="relative hidden lg:block">

                <!-- MAIN CARD -->
                <div class="relative bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-[32px] p-8">

                    <!-- TOP -->
                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <p class="text-sm text-gray-500">
                                Rekomendasi Hari Ini
                            </p>

                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                Kos Premium
                            </h3>
                        </div>

                        <div class="bg-brand-100 text-brand text-sm font-semibold px-4 py-2 rounded-full">
                            Best Choice
                        </div>

                    </div>

                    <!-- IMAGE -->
                    <div class="rounded-3xl overflow-hidden h-64 bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">

                        <svg class="w-28 h-28 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>

                    </div>

                    <!-- INFO -->
                    <div class="mt-8 space-y-5">

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">
                                Lokasi
                            </span>

                            <span class="font-semibold text-gray-900">
                                Yogyakarta
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">
                                Fasilitas
                            </span>

                            <span class="font-semibold text-gray-900">
                                WiFi • AC • Kamar Mandi
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">
                                Harga
                            </span>

                            <span class="text-2xl font-extrabold text-brand">
                                Rp850k
                            </span>
                        </div>

                    </div>

                </div>

                <!-- FLOATING CARD -->
                <div class="absolute -bottom-8 -left-10 bg-white rounded-2xl shadow-2xl border border-gray-100 p-5">

                    <div class="flex items-center gap-4">

                        <div class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white">
                            ✓
                        </div>

                        <div>
                            <p class="font-bold text-gray-900">
                                Booking Mudah
                            </p>

                            <p class="text-sm text-gray-500">
                                Proses cepat & aman
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php if (!empty($reviews)): ?>
<section class="bg-gray-50 py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Apa Kata Mereka?</h2>
            <p class="mt-4 text-gray-500 text-sm">Ulasan jujur langsung dari penghuni yang sudah merasakan kenyamanan tinggal di kos pilihan kami.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($reviews as $rev): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-5 h-5 <?= $i <= $rev['rating'] ? 'text-yellow-400' : 'text-gray-200' ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>

                        <p class="text-gray-600 text-sm italic leading-relaxed mb-6">
                            "<?= htmlspecialchars($rev['comment'] ?: 'Tidak ada komentar tertulis.') ?>"
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand font-bold flex items-center justify-center text-sm">
                            <?= strtoupper(substr($rev['user_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($rev['user_name']) ?></h4>
                            <p class="text-xs text-brand font-medium mt-0.5"><?= htmlspecialchars($rev['room_name']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>