<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-300 to-blue-500 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white/10 backdrop-blur-xl border border-white/30 text-white px-8 py-12 rounded-xl shadow-2xl w-full max-w-md relative">

        {{-- Logo SIDAMAKMUR --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="SIDAMAKMUR Logo" class="w-24 h-auto object-contain">
        </div>

        {{-- Icon user --}}
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-blue-600 rounded-full p-4 border-4 border-green-200 shadow-lg">
            <i class="fa-solid fa-user text-white text-3xl"></i>
        </div>

        <h2 class="text-center text-2xl font-bold mb-8 mt-4"> Halaman Login
        </h2>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-red-200 text-red-800 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="admin">

            {{-- Email --}}
            <div>
                <label class="text-sm mb-1 block">Email</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full py-2 pl-10 pr-4 rounded-lg bg-white text-black placeholder:text-gray-500 focus:ring-2 focus:ring-green-400">
                    <i class="fa-solid fa-envelope absolute top-2.5 left-3 text-gray-500"></i>
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="text-sm mb-1 block">Password</label>
                <div class="relative">
                    <input type="password" name="password" required
                        class="w-full py-2 pl-10 pr-4 rounded-lg bg-white text-black placeholder:text-gray-500 focus:ring-2 focus:ring-green-400">
                    <i class="fa-solid fa-lock absolute top-2.5 left-3 text-gray-500"></i>
                </div>
            </div>

            {{-- Tombol Login --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition py-2 rounded-lg font-semibold text-white">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
            </button>
        </form>


    </div>

    {{-- Script Role --}}
    <script>
        function setRole(role) {
            document.getElementById('roleInput').value = role;
            document.getElementById('roleText').textContent = role.charAt(0).toUpperCase() + role.slice(1);

            // Toggle active button style
            document.getElementById('btn-admin').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('btn-pemilik').classList.remove('bg-blue-600', 'text-white');

            if (role === 'admin') {
                document.getElementById('btn-admin').classList.add('bg-blue-600', 'text-white');
            } else {
                document.getElementById('btn-pemilik').classList.add('bg-blue-600', 'text-white');
            }
        }

        window.onload = () => setRole('admin');
    </script>

</body>
</html>
