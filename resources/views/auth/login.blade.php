<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="w-full">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-200 mb-1">Email Address</label>
            <input id="email" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="email" name="email" value="{{ old('email') }}" placeholder="admin@paperboy.com" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block font-medium text-sm text-gray-200 mb-1">Password</label>
            <input id="password" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-white border-transparent text-[#00AEEF] focus:ring-[#00AEEF] focus:ring-offset-[#1B2430] w-4 h-4 cursor-pointer" name="remember">
                <span class="ml-2 text-sm text-gray-400 hover:text-gray-200 transition-colors">Remember me</span>
            </label>

            <a class="text-sm text-gray-400 hover:text-[#00AEEF] transition-colors" href="{{ route('register') }}">
                Not registered?
            </a>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#00AEEF] hover:bg-[#0096D1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] focus:ring-offset-[#1B2430] transition-colors">
            Sign In to Dashboard
        </button>
    </form>
</x-guest-layout>
