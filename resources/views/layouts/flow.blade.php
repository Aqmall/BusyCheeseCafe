<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi - Busy Chesee Cafe</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        body, p, a, span, div, button, input, select, label { font-family: 'Open Sans', sans-serif; }
    </style>
</head>
<body class="bg-white">
    @php
        // Definisikan semua langkah dalam alur reservasi
        $steps = [
            1 => ['title' => 'Pilih Meja', 'desc' => 'Denah visual & pilih meja'],
            2 => ['title' => 'Pilih Menu', 'desc' => 'Order makanan & minuman'],
            3 => ['title' => 'Data Diri', 'desc' => 'Isi data & konfirmasi'],
            4 => ['title' => 'Pembayaran', 'desc' => 'Bayar & selesai'],
        ];
    @endphp
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <header class="flex items-center justify-between border-b pb-4 mb-8">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-black flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                Kembali
            </a>
            <div class="flex items-center space-x-2 md:space-x-4">
                @foreach($steps as $number => $step)
                    <div class="flex items-center">
                        <div class="flex items-center space-x-2 {{ $currentStep >= $number ? 'text-cafe-accent' : 'text-gray-400' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 font-bold {{ $currentStep >= $number ? 'border-cafe-accent bg-cafe-accent text-white' : 'border-gray-300' }}">
                                @if($currentStep > $number)
                                    {{-- Ikon Centang --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                @else
                                    {{ $number }}
                                @endif
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium">{{ $step['title'] }}</p>
                            </div>
                        </div>
                        @if(!$loop->last)
                             {{-- Ikon Panah --}}
                            <svg class="h-5 w-5 text-gray-300 mx-2 md:mx-4 hidden sm:block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        @endif
                    </div>
                @endforeach
            </div>
        </header>
        
        <main>
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>