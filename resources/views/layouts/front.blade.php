<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOR Raden Ganda - Web Profile & Booking</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- NAVBAR HALAMAN DEPAN -->
    <nav class="bg-indigo-900 text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider">GOR RADEN GANDA</a>
            <div class="space-x-6 flex items-center">
                <a href="{{ route('home') }}" class="hover:text-amber-400 transition text-sm">Beranda</a>
                <a href="{{ route('fasilitas') }}" class="hover:text-amber-400 transition text-sm">Fasilitas</a>
                <a href="{{ route('harga') }}" class="hover:text-amber-400 transition text-sm">Harga</a>
                <a href="{{ route('kontak') }}" class="hover:text-amber-400 transition text-sm">Kontak</a>
                <a href="{{ route('booking.create') }}" class="bg-amber-500 text-indigo-950 font-bold px-4 py-2 rounded text-sm hover:bg-amber-400 transition">Booking Lapangan</a>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm border-t border-gray-800">
        <p>&copy; {{ date('Y') }} GOR Badminton Raden Ganda. All Rights Reserved.</p>
    </footer>

</body>
</html>