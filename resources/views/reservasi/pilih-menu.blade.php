@extends('layouts.flow', ['currentStep' => 2])

@section('content')
<div x-data="menuCart()">
    <div class="text-left mb-8">
        <h2 class="text-3xl font-bold text-cafe-secondary mb-2">Pilih Menu</h2>
        <p class="text-gray-600">Pesan makanan & minuman untuk reservasi Anda.</p>
    </div>
    
    @error('menu_items')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @enderror

    <form action="{{ route('reservasi.flow.menu.store') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Daftar Menu --}}
            <div class="lg:col-span-2 space-y-8">
                @foreach($menus as $kategori => $items)
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-cafe-accent border-b-2 border-cafe-beige pb-2">{{ $kategori }}</h4>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($items as $menu)
                        <div class="border rounded-lg p-4 flex space-x-4">
                            <img src="{{ asset($menu->image_url) }}" alt="{{ $menu->namaMenu }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h5 class="font-semibold text-sm mb-1 truncate">{{ $menu->namaMenu }}</h5>
                                    <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center space-x-2 mt-2">
                                    <button type="button" @click="removeFromCart({{ $menu->id }})" class="w-7 h-7 p-0 border rounded-md text-lg">-</button>
                                    <input type="number" x-model.number="getQuantity({{ $menu->id }})" readonly class="w-10 text-center font-semibold border-0 bg-transparent">
                                    <button type="button" @click="addToCart({{ $menu->id }}, '{{ $menu->namaMenu }}', {{ $menu->harga }})" class="w-7 h-7 p-0 border rounded-md text-lg">+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
    
            {{-- Kolom Kanan: Keranjang Pesanan --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 border rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-bold">Keranjang Pesanan</h3>
                    </div>
                    <div class="p-4 space-y-4 max-h-80 overflow-y-auto">
                        <template x-if="cart.length === 0">
                            <p class="text-gray-500 text-center py-8">Belum ada pesanan</p>
                        </template>
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <p class="text-xs text-gray-600" x-text="'Rp ' + item.price.toLocaleString()"></p>
                                </div>
                                <div class="flex items-center space-x-2 ml-2">
                                    <span class="text-sm font-medium w-6 text-center" x-text="item.quantity">x</span>
                                </div>
                                {{-- Hidden inputs for form submission --}}
                                <input type="hidden" :name="`menu_items[${item.id}][id]`" :value="item.id">
                                <input type="hidden" :name="`menu_items[${item.id}][jumlah]`" :value="item.quantity">
                            </div>
                        </template>
                    </div>
                    <div class="p-4 border-t space-y-2 bg-gray-50">
                         <div class="flex justify-between font-semibold">
                            <span>Total:</span>
                            <span x-text="'Rp ' + getCartTotal().toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tombol Navigasi --}}
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('reservasi.flow.meja.show') }}" class="text-gray-600 font-semibold hover:text-black">&larr; Sebelumnya</a>
            <button type="submit" class="bg-cafe-primary text-cafe-secondary font-bold py-3 px-8 rounded-lg hover:bg-cafe-primary-dark">
                Selanjutnya
            </button>
        </div>
    </form>
</div>
    
{{-- Alpine.js untuk Interaktivitas Keranjang --}}
@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function menuCart() {
        return {
            cart: [],
            addToCart(id, name, price) {
                const existingItem = this.cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    this.cart.push({ id, name, price, quantity: 1 });
                }
            },
            removeFromCart(id) {
                const existingItem = this.cart.find(item => item.id === id);
                if (existingItem && existingItem.quantity > 1) {
                    existingItem.quantity--;
                } else {
                    this.cart = this.cart.filter(item => item.id !== id);
                }
            },
            getCartTotal() {
                return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            },
            getQuantity(id) {
                const item = this.cart.find(i => i.id === id);
                return item ? item.quantity : 0;
            }
        }
    }
</script>
@endpush
@endsection