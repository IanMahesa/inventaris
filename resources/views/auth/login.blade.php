<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="flex flex-col min-h-screen w-full items-center justify-center bg-cover bg-no-repeat bg-center px-4 bg-[url('{{ asset('assets/img/back.png') }}')]">

        <div class="w-full max-w-md rounded-xl bg-gray-800 bg-opacity-70 px-8 py-10 shadow-lg backdrop-blur-md text-white space-y-6">

            <!-- Header -->
            <div class="text-center">
                <img src="{{ asset('assets/img/Logo.png') }}" width="80" class="mx-auto mb-2" />
                <h1 class="text-2xl font-bold mb-1">Inventarisasi</h1>
                <p class="text-gray-300 text-sm">Silahkan login terlebih dahulu</p>
            </div>

            <!-- Error Message -->
            @if(session('error'))
            <div class="text-red-400 text-sm text-center">
                {{ session('error') }}
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Username -->
                <div>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full rounded-full border-none bg-white bg-opacity-20 px-6 py-2 text-white placeholder-slate-200 shadow-lg outline-none backdrop-blur-md"
                        placeholder="Username">
                    @error('username')
                    <span class="text-red-400 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative">
                    <input type="password" name="password" id="passwordInput" required
                        class="w-full rounded-full border-none bg-white bg-opacity-20 px-6 py-2 pr-10 text-white placeholder-slate-200 shadow-lg outline-none backdrop-blur-md"
                        placeholder="Password">
                    <div class="absolute inset-y-0 right-4 flex items-center cursor-pointer" onclick="togglePassword()">
                        <i class="fas fa-eye text-white" id="togglePasswordIcon"></i>
                    </div>
                    @error('password')
                    <span class="text-red-400 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="form-checkbox h-4 w-4 text-yellow-400 transition duration-150 ease-in-out">
                    <label for="remember" class="text-sm text-gray-200">Remember Me</label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center pt-2">
                    <button type="submit"
                        class="rounded-full bg-blue-400 px-10 py-2 text-white shadow-xl transition-colors duration-300 hover:bg-blue-500">
                        Login
                    </button>
                </div>
            </form>

        </div>
    </div>


    <!-- Toggle Password Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>