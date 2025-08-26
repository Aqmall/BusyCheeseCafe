@extends('layouts.dashboard')

@section('content')
    <div class="text-center bg-white p-12 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Segera Hadir!</h2>
        <p class="text-gray-600 text-lg">Halaman yang Anda tuju sedang dalam tahap pengembangan.</p>
        <div class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-6 rounded-lg hover:bg-cafe-primary-dark">
                &larr; Kembali ke Dasbor
            </a>
        </div>
    </div>
@endsection