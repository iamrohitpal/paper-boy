<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('settings.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                {{ __('WhatsApp Integration') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Glassmorphism Card -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/40 dark:border-gray-700 overflow-hidden relative">
                
                <!-- Decorative background blobs -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-green-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-blue-400/20 blur-3xl pointer-events-none"></div>

                <div class="p-8 md:p-12 text-gray-900 dark:text-gray-100 flex flex-col items-center justify-center min-h-[500px] relative z-10">
                    
                    <div class="text-center mb-10">
                        <h3 class="text-3xl font-black mb-4 bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-500">{{ __('messages.link_your_whatsapp') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-lg max-w-lg mx-auto font-medium">
                            {{ __('messages.link_whatsapp_desc') }}
                        </p>
                    </div>

                    <!-- Main Status Container -->
                    <div id="whatsapp-status-container" class="bg-white dark:bg-gray-900 p-8 md:p-10 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 flex flex-col items-center border border-gray-100 dark:border-gray-700 w-full max-w-md relative min-h-[400px] transition-all">
                        
                        <!-- Loading State -->
                        <div id="status-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 dark:bg-gray-900/90 rounded-3xl z-20 backdrop-blur-sm">
                            <div class="relative w-20 h-20 mb-6">
                                <div class="absolute inset-0 rounded-full border-4 border-gray-100 dark:border-gray-800"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-green-500 border-t-transparent animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </div>
                            </div>
                            <span class="text-gray-800 dark:text-gray-200 font-bold text-lg animate-pulse">{{ __('messages.checking_connection') }}</span>
                        </div>

                        <!-- Disconnected / Error State -->
                        <div id="status-disconnected" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center px-8 bg-white dark:bg-gray-900 rounded-3xl z-10">
                            <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/20 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-xl font-black text-rose-600 dark:text-rose-400 mb-3">{{ __('messages.service_unavailable') }}</h4>
                            <p class="text-sm font-medium text-gray-500 mb-8">{{ __('messages.whatsapp_bridging_error') }}</p>
                            <button onclick="checkStatus()" class="w-full px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-100 shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                {{ __('messages.retry') }}
                            </button>
                        </div>

                        <!-- QR Ready State / Login State -->
                        <div id="status-qr" class="hidden flex-col items-center w-full z-10">
                            
                            <!-- Custom Tab Switcher -->
                            <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl mb-8 w-full">
                                <button type="button" onclick="switchTab('qr')" id="tab-qr" class="flex-1 py-2.5 px-4 rounded-lg text-sm font-bold shadow bg-white dark:bg-gray-700 text-green-600 dark:text-green-400 transition-all">
                                    {{ __('messages.scan_qr_code') }}
                                </button>
                                <button type="button" onclick="switchTab('phone')" id="tab-phone" class="flex-1 py-2.5 px-4 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-all">
                                    {{ __('messages.phone_number') }}
                                </button>
                            </div>

                            <div id="view-qr" class="flex flex-col items-center w-full">
                                <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100 mb-6 w-64 h-64 flex items-center justify-center relative group">
                                    <!-- Scan animation line -->
                                    <div class="absolute inset-x-4 top-4 h-0.5 bg-green-500 shadow-[0_0_8px_2px_rgba(34,197,94,0.5)] opacity-50 hidden group-hover:block animate-[scan_2s_ease-in-out_infinite]"></div>
                                    <img id="qr-code-img" src="" alt="WhatsApp QR Code" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 shadow-sm">
                                    <span class="relative flex h-3 w-3 mr-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                    </span>
                                    {{ __('messages.awaiting_scan') }}
                                </div>
                            </div>

                            <div id="view-phone" class="hidden flex-col items-center w-full">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center mb-6">{{ __('messages.enter_phone_number_desc') }}</p>
                                <div class="w-full mb-6">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <input type="text" id="phone_input" placeholder="e.g. +919876543210" class="pl-11 block w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white shadow-inner focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:bg-white dark:focus:bg-gray-900 transition-all font-semibold text-lg py-3">
                                    </div>
                                </div>
                                <button type="button" onclick="requestPairingCode()" id="btn-request-code" class="w-full px-6 py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transform hover:-translate-y-0.5 transition-all">
                                    {{ __('messages.get_pairing_code') }}
                                </button>

                                <div id="pairing-code-display" class="hidden flex-col items-center w-full bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 mt-2">
                                    <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-3 text-center uppercase tracking-wider">{{ __('messages.enter_code_on_phone') }}</p>
                                    <div class="bg-white dark:bg-gray-900 px-8 py-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 tracking-[0.3em] text-3xl font-mono font-black text-green-600 dark:text-green-400 shadow-sm w-full text-center" id="pairing-code-text">
                                        --------
                                    </div>
                                    <div class="mt-6 inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 shadow-sm">
                                        <span class="relative flex h-3 w-3 mr-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                        </span>
                                        {{ __('messages.awaiting_connection') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connected State -->
                        <div id="status-connected" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-white dark:bg-gray-900 rounded-3xl z-10 px-8 text-center">
                            <div class="relative mb-8">
                                <div class="absolute inset-0 bg-green-400 rounded-full blur-xl opacity-30 animate-pulse"></div>
                                <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center relative shadow-xl border-4 border-white dark:border-gray-900">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                            <h4 class="text-3xl font-black text-gray-900 dark:text-white mb-3">{{ __('messages.connected') }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-10 text-lg">{{ __('messages.whatsapp_linked_successfully') }}</p>
                            
                            <form action="{{ route('whatsapp.logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3.5 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 font-bold rounded-xl border-2 border-rose-100 dark:border-rose-900/50 hover:bg-rose-50 dark:hover:bg-rose-900/20 shadow-sm transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('messages.disconnect_device') }}
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes scan {
            0% { transform: translateY(0); }
            50% { transform: translateY(220px); }
            100% { transform: translateY(0); }
        }
    </style>

    <script>
        let pollingInterval = null;

        function switchTab(tab) {
            if (tab === 'qr') {
                document.getElementById('view-qr').classList.remove('hidden');
                document.getElementById('view-qr').classList.add('flex');
                document.getElementById('view-phone').classList.add('hidden');
                document.getElementById('view-phone').classList.remove('flex');
                
                document.getElementById('tab-qr').classList.add('shadow', 'bg-white', 'dark:bg-gray-700', 'text-green-600', 'dark:text-green-400');
                document.getElementById('tab-qr').classList.remove('text-gray-500');
                
                document.getElementById('tab-phone').classList.remove('shadow', 'bg-white', 'dark:bg-gray-700', 'text-green-600', 'dark:text-green-400');
                document.getElementById('tab-phone').classList.add('text-gray-500');
            } else {
                document.getElementById('view-phone').classList.remove('hidden');
                document.getElementById('view-phone').classList.add('flex');
                document.getElementById('view-qr').classList.add('hidden');
                document.getElementById('view-qr').classList.remove('flex');
                
                document.getElementById('tab-phone').classList.add('shadow', 'bg-white', 'dark:bg-gray-700', 'text-green-600', 'dark:text-green-400');
                document.getElementById('tab-phone').classList.remove('text-gray-500');
                
                document.getElementById('tab-qr').classList.remove('shadow', 'bg-white', 'dark:bg-gray-700', 'text-green-600', 'dark:text-green-400');
                document.getElementById('tab-qr').classList.add('text-gray-500');
            }
        }

        function requestPairingCode() {
            const phone = document.getElementById('phone_input').value;
            if (!phone) {
                alert('Please enter a phone number');
                return;
            }

            const btn = document.getElementById('btn-request-code');
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Requesting...`;

            fetch('{{ route("whatsapp.pair") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone_number: phone })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('btn-request-code').classList.add('hidden');
                    document.getElementById('phone_input').disabled = true;
                    document.getElementById('phone_input').classList.add('opacity-50', 'cursor-not-allowed');
                    document.getElementById('pairing-code-display').classList.remove('hidden');
                    document.getElementById('pairing-code-display').classList.add('flex');
                    document.getElementById('pairing-code-text').innerText = data.code.match(/.{1,4}/g).join('-');
                } else {
                    alert(data.error || 'Failed to request pairing code');
                    btn.disabled = false;
                    btn.innerText = '{{ __('messages.get_pairing_code') }}';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error requesting code');
                btn.disabled = false;
                btn.innerText = '{{ __('messages.get_pairing_code') }}';
            });
        }

        function checkStatus() {
            document.getElementById('status-loading').classList.remove('hidden');
            document.getElementById('status-disconnected').classList.add('hidden');
            document.getElementById('status-qr').classList.add('hidden');
            document.getElementById('status-connected').classList.add('hidden');

            fetch('{{ route("whatsapp.status") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('status-loading').classList.add('hidden');
                    
                    if (data.status === 'connected') {
                        document.getElementById('status-connected').classList.remove('hidden');
                        document.getElementById('status-connected').classList.add('flex');
                        stopPolling(); // Connected, no need to poll quickly
                    } else if (data.status === 'qr_ready') {
                        document.getElementById('status-qr').classList.remove('hidden');
                        document.getElementById('status-qr').classList.add('flex');
                        document.getElementById('qr-code-img').src = data.qr;
                        startPolling(); // Keep polling to see if user scanned it
                    } else if (data.status === 'initializing') {
                        document.getElementById('status-loading').classList.remove('hidden');
                        startPolling(); // Keep polling until it's ready
                    } else {
                        document.getElementById('status-disconnected').classList.remove('hidden');
                        stopPolling();
                    }
                })
                .catch(error => {
                    document.getElementById('status-loading').classList.add('hidden');
                    document.getElementById('status-disconnected').classList.remove('hidden');
                    stopPolling();
                });
        }

        function startPolling() {
            if (!pollingInterval) {
                pollingInterval = setInterval(() => {
                    fetch('{{ route("whatsapp.status") }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'connected') {
                                checkStatus(); // Re-render to show connected UI
                            } else if (data.status === 'qr_ready') {
                                if (document.getElementById('status-loading').classList.contains('hidden') === false) {
                                    checkStatus(); // Re-render to switch from loading to QR view
                                } else if (document.getElementById('qr-code-img').src !== data.qr) {
                                    document.getElementById('qr-code-img').src = data.qr; // Update QR if it changed
                                }
                            }
                        })
                        .catch(err => console.error(err));
                }, 3000); // Poll every 3 seconds
            }
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        // Initial check on load
        document.addEventListener('DOMContentLoaded', checkStatus);
    </script>
</x-app-layout>