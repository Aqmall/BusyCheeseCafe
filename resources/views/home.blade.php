@extends('layouts.app')

@section('content')
<section id="home" class="relative bg-white py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <h1 class="text-4xl md:text-6xl font-bold text-cafe-secondary leading-tight">
                    Pesan Tempatmu di <span class="text-cafe-primary">Surga Keju</span> Blok M
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Nikmati pengalaman kuliner terbaik dengan cheesecake premium dan kopi artisan di suasana yang hangat dan modern.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#reservation" class="inline-block bg-cafe-primary text-cafe-secondary hover:bg-cafe-primary-dark px-8 py-3 text-lg font-semibold rounded-xl text-center">
                        Reservasi Sekarang
                    </a>
                </div>
            </div>
            <div class="relative hidden lg:block">
                <div class="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                    <img src="img/interior-bcc.jpeg" alt="Busy Cheese Cafe Interior" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="reservation" class="py-20 bg-cafe-beige">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-cafe-secondary mb-4">Buat Reservasi</h2>
            <p class="text-xl text-gray-600">Pastikan tempat duduk favorit Anda tersedia</p>
        </div>
        @include('partials.reservation-form')
    </div>
</section>

<section id="menu" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-cafe-secondary mb-4">Menu Favorit</h2>
            <p class="text-xl text-gray-600">Signature cheesecakes dan kopi artisan pilihan</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($featuredMenus as $menu)
            <div class="shadow-lg border-0 bg-white hover:shadow-xl transition-all duration-300 group rounded-lg">
                <div class="aspect-[4/3] overflow-hidden rounded-t-lg">
                    <img src="{{ asset($menu->image_url) }}" alt="{{ $menu->namaMenu }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-cafe-secondary mb-2">{{ $menu->namaMenu }}</h3>
                    <span class="text-lg font-bold text-cafe-accent">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <p>Menu favorit akan segera ditampilkan.</p>
            @endforelse
        </div>
    </div>
</section>

<section id="location" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            
            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-cafe-secondary mb-4">Kunjungi Kami</h2>
                    <p class="text-xl text-gray-600">Temukan kami di jantung Blok M, Jakarta Selatan.</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 pt-1">
                            <svg class="h-6 w-6 text-cafe-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-cafe-secondary mb-1">Alamat</h4>
                            <p class="text-gray-600">Jl. Iskandarsyah II No.Raya Lantai LG #W18, Melawai, Kebayoran Baru, Jakarta Selatan, 12160</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 pt-1">
                            <svg class="h-6 w-6 text-cafe-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-cafe-secondary mb-1">Jam Operasional</h4>
                            <p class="text-gray-600">Setiap Hari: 11:00 - 23:00</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 pt-1">
                           <svg class="h-6 w-6 text-cafe-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-cafe-secondary mb-1">Kontak</h4>
                            <p class="text-gray-600">+62 21 1234 5678</p>
                            <p class="text-gray-600">info@busycheesecafe.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-xl aspect-w-16 aspect-h-9 h-full min-h-[400px]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1983.072407712274!2d106.80179983491821!3d-6.244638818358475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f10007e1a3fb%3A0xb8e910b34570830e!2sBusy%20Cheese%20Cafe!5e0!3m2!1sen!2sid!4v1754003935435!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-full h-full"></iframe>
            </div>

        </div>
    </div>
</section>
@endsection