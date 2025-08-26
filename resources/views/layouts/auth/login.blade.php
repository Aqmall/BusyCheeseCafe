@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Staff Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Masuk untuk mengakses sistem manajemen cafe
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xl">
            <form class="space-y-6" action="{{ route('login.perform') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-cafe-primary focus:border-cafe-primary sm:text-sm" placeholder="Username">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-cafe-primary focus:border-cafe-primary sm:text-sm" placeholder="Password">
                    </div>
                </div>

                @if($errors->any())
                    <div class="text-red-500 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-cafe-secondary bg-cafe-primary hover:bg-cafe-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cafe-primary-dark">
                        Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm">
                <p class="font-semibold text-gray-700 mb-2">Demo Credentials:</p>
                <div class="space-y-1 text-gray-600">
                    <p>• <strong>Kasir/Pelayan:</strong> kasir / password</p>
                    <p>• <strong>Manager:</strong> manager / password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection