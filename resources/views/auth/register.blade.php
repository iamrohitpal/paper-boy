<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="w-full">
        @csrf

        @if ($errors->has('error'))
            <div class="mb-4 p-3 bg-red-900/50 border border-red-500 text-red-200 text-sm rounded-md">
                {{ $errors->first('error') }}
            </div>
        @endif

        <!-- Business Name -->
        <div class="mb-4">
            <label for="business_name" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.business_agency_name') }}</label>
            <input id="business_name" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="text" name="business_name" value="{{ old('business_name') }}" placeholder="ABC Newspaper Agency" required autofocus autocomplete="organization" />
            @error('business_name')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.owner_name') }}</label>
            <input id="name" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autocomplete="name" />
            @error('name')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mobile Number -->
        <div class="mb-4">
            <label for="mobile" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.mobile_number') }}</label>
            <input id="mobile" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="tel" name="mobile" value="{{ old('mobile') }}" placeholder="9876543210" required autocomplete="tel" />
            @error('mobile')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.email_address') }}</label>
            <input id="email" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="email" name="email" value="{{ old('email') }}" placeholder="admin@paperboy.com" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.password') }}</label>
            <input id="password" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.confirm_password') }}</label>
            <input id="password_confirmation" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Language Preference -->
        <div class="mb-8">
            <label for="language" class="block font-medium text-sm text-gray-200 mb-1">{{ __('messages.preferred_language') }}</label>
            <select id="language" name="language" class="block w-full bg-[#253040] border border-transparent text-gray-200 focus:border-[#00AEEF] focus:ring-[#00AEEF] rounded-md shadow-sm py-2 px-3 focus:outline-none transition-colors" required>
                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                <option value="hi" {{ old('language') == 'hi' ? 'selected' : '' }}>हिंदी (Hindi)</option>
            </select>
            @error('language')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-8">
            <a class="text-sm text-gray-400 hover:text-[#00AEEF] transition-colors" href="{{ route('login') }}">
                {{ __('messages.already_registered') }}
            </a>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#00AEEF] hover:bg-[#0096D1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] focus:ring-offset-[#1B2430] transition-colors">
            {{ __('messages.register_account') }}
        </button>
    </form>
</x-guest-layout>
