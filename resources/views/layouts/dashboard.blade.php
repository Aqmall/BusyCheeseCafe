<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor - Busy Chesee Cafe</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <aside class="w-64 bg-white shadow-md flex-shrink-0">
            <div class="p-4 border-b">
                <a href="{{ route('home') }}" class="text-xl font-bold text-cafe-secondary">Busy Cheese Cafe</a>
                <p class="text-xs text-gray-500">Staff Dashboard</p>
            </div>
            {{-- NAVIGASI DIPERBARUI DENGAN TAUTAN AKTIF --}}
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Denah Meja
                </a>
                <a href="{{ route('admin.reservasi.list') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Daftar Reservasi
                </a>
                <a href="{{ route('admin.reservasi.upcoming') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Reservasi Akan Datang
                </a>
                <a href="{{ route('admin.walkin.list') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Daftar Walk-in
                </a>
                <a href="{{ route('admin.reservasi.canceled') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Batal Reservasi
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-cafe-beige hover:text-cafe-secondary font-semibold">
                    Laporan
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b p-4 flex justify-end items-center space-x-4">
                <div class="px-3 py-1 bg-cafe-beige text-cafe-secondary rounded-full text-sm font-semibold capitalize">
                    {{ Auth::user()->name ?? 'Staf' }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="font-semibold text-gray-600 hover:text-red-500">Logout</button>
                </form>
            </header>
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>