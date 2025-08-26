<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busy Chesee Cafe - Reservasi</title>
    
    {{-- Baris ini yang paling penting untuk memuat CSS dari Vite --}}
    @vite('resources/css/app.css')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        body, p, a, span, div, button, input, select, label { font-family: 'Open Sans', sans-serif; }
    </style>
</head>
<body class="bg-cafe-beige text-cafe-secondary">
    
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center space-x-3">
                    <svg class="h-8 w-8 text-cafe-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.58 16.09l-1.09-7.66C20.21 6.46 18.52 5 16.53 5H7.47C5.48 5 3.79 6.46 3.51 8.43l-1.09 7.66C2.2 17.63 3.39 19 4.99 19H19.01c1.6 0 2.79-1.37 2.57-2.91zM9 12V8h6v4h-2v2h-2v-2H9z"/></svg>
                    <span class="text-2xl font-bold text-cafe-secondary">Busy Cheese Cafe</span>
                </a>
                
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-cafe-secondary hover:text-cafe-primary transition-colors">Home</a>
                    <a href="#reservation" class="text-cafe-secondary hover:text-cafe-primary transition-colors">Reservasi</a>
                    <a href="#menu" class="text-cafe-secondary hover:text-cafe-primary transition-colors">Menu</a>
                    <a href="#location" class="text-cafe-secondary hover:text-cafe-primary transition-colors">Lokasi</a>

                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

<footer class="bg-cafe-secondary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cafe-primary rounded flex items-center justify-center">
                        {{-- Icon Chef Hat --}}
                        <svg class="h-6 w-6 text-cafe-secondary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-4.5 0V6.75A2.25 2.25 0 0010.5 4.5h-3a2.25 2.25 0 00-2.25 2.25v3.75m4.5 0h3.75m-3.75 0V10.5m0 0H9m3.75 0a2.25 2.25 0 012.25 2.25v3.75a2.25 2.25 0 01-2.25 2.25h-3a2.25 2.25 0 01-2.25-2.25V10.5a2.25 2.25 0 012.25-2.25h3.75M9 15h2.25" /></svg>
                    </div>
                    <h3 class="text-xl font-bold">Busy Cheese Cafe</h3>
                </div>
                <p class="text-gray-300">
                    Your premium destination for artisan cheesecakes and specialty coffee in Blok M.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="#home" class="hover:text-cafe-primary transition-colors">Home</a></li>
                    <li><a href="#reservation" class="hover:text-cafe-primary transition-colors">Reservasi</a></li>
                    <li><a href="#menu" class="hover:text-cafe-primary transition-colors">Menu</a></li>
                    <li><a href="#location" class="hover:text-cafe-primary transition-colors">Lokasi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Contact Info</h4>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-center space-x-3">
                        {{-- Icon Phone --}}
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        <span>+62 21 1234 5678</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        {{-- Icon Mail --}}
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        <span>info@busycheesecafe.com</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        {{-- Icon MapPin --}}
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        <span>Blok M, Jakarta Selatan</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 Busy Cheese Cafe. All rights reserved.</p>
        </div>
    </div>
</footer>

    @stack('scripts')
</body>
</html>