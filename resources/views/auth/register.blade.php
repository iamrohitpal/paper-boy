<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="w-full">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-gray-200 mb-1">Name</label>
            <input id="name" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-200 mb-1">Email Address</label>
            <input id="email" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="email" name="email" value="{{ old('email') }}" placeholder="admin@paperboy.com" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-gray-200 mb-1">Password</label>
            <input id="password" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-8">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-200 mb-1">Confirm Password</label>
            <input id="password_confirmation" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-8">
            <a class="text-sm text-gray-400 hover:text-[#00AEEF] transition-colors" href="{{ route('login') }}">
                Already registered?
            </a>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#00AEEF] hover:bg-[#0096D1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] focus:ring-offset-[#1B2430] transition-colors">
            Register Account
        </button>
    </form>
</x-guest-layout>
