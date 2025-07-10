<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Prokerala API Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Prokerala API Configuration</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Configure Prokerala API credentials. You can find your client id/secret on your
                            <a href="https://api.prokerala.com/account/client" target="_blank" class="text-blue-600 hover:text-blue-800 underline">dashboard</a>.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="prokerala_client_id" :value="__('Client ID')" />
                                <x-text-input id="prokerala_client_id" name="prokerala_client_id" type="text"
                                              class="mt-1 block w-full font-mono"
                                              pattern="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
                                              placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                                              autocomplete="off"
                                              onchange="validateClientId()"
                                              oninput="onClientIdInput()"
                                              :value="old('prokerala_client_id', $settings['prokerala_client_id'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('prokerala_client_id')" />
                                <p class="mt-1 text-sm text-gray-500">Your Prokerala API client ID (UUID format)</p>
                                <div id="client-id-validation" class="mt-1 text-sm"></div>
                            </div>

                            <div>
                                <x-input-label for="prokerala_client_secret" :value="__('Client Secret')" />
                                <x-text-input id="prokerala_client_secret" name="prokerala_client_secret" type="password"
                                              class="mt-1 block w-full font-mono"
                                              autocomplete="off"
                                              :value="old('prokerala_client_secret', $settings['prokerala_client_secret'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('prokerala_client_secret')" />
                                <p class="mt-1 text-sm text-gray-500">Your Prokerala API client secret</p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Site Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Site Information</h3>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="site_name" :value="__('Site Name')" />
                                <x-text-input id="site_name" name="site_name" type="text"
                                              class="mt-1 block w-full" :value="old('site_name', $settings['site_name'] ?? 'Divine Astrology')" />
                                <x-input-error class="mt-2" :messages="$errors->get('site_name')" />
                            </div>

                            <div>
                                <x-input-label for="site_description" :value="__('Site Description')" />
                                <textarea id="site_description" name="site_description" rows="3"
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('site_description', $settings['site_description'] ?? 'Your trusted source for Vedic astrology consultations and personalized horoscope readings.') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('site_description')" />
                            </div>

                            <div>
                                <x-input-label for="contact_email" :value="__('Contact Email')" />
                                <x-text-input id="contact_email" name="contact_email" type="email"
                                              class="mt-1 block w-full" :value="old('contact_email', $settings['contact_email'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                            </div>

                            <div>
                                <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                                <x-text-input id="contact_phone" name="contact_phone" type="text"
                                              class="mt-1 block w-full" :value="old('contact_phone', $settings['contact_phone'] ?? '')"
                                              placeholder="+1 (555) 123-4567" />
                                <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                            </div>

                            <div>
                                <x-input-label for="contact_address" :value="__('Contact Address')" />
                                <x-text-input id="contact_address" name="contact_address" type="text"
                                              class="mt-1 block w-full" :value="old('contact_address', $settings['contact_address'] ?? '')"
                                              placeholder="City, Country" />
                                <x-input-error class="mt-2" :messages="$errors->get('contact_address')" />
                            </div>

                            <div>
                                <x-input-label for="whatsapp_number" :value="__('WhatsApp Number')" />
                                <x-text-input id="whatsapp_number" name="whatsapp_number" type="text"
                                              class="mt-1 block w-full" :value="old('whatsapp_number', $settings['whatsapp_number'] ?? '')"
                                              placeholder="1234567890" />
                                <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
                                <p class="mt-1 text-sm text-gray-500">Include country code without + prefix (e.g., 1234567890)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="facebook_url" :value="__('Facebook URL')" />
                                <x-text-input id="facebook_url" name="facebook_url" type="url"
                                              class="mt-1 block w-full" :value="old('facebook_url', $settings['facebook_url'] ?? '')"
                                              placeholder="https://facebook.com/yourpage" />
                                <x-input-error class="mt-2" :messages="$errors->get('facebook_url')" />
                            </div>

                            <div>
                                <x-input-label for="twitter_url" :value="__('Twitter URL')" />
                                <x-text-input id="twitter_url" name="twitter_url" type="url"
                                              class="mt-1 block w-full" :value="old('twitter_url', $settings['twitter_url'] ?? '')"
                                              placeholder="https://twitter.com/yourhandle" />
                                <x-input-error class="mt-2" :messages="$errors->get('twitter_url')" />
                            </div>

                            <div>
                                <x-input-label for="instagram_url" :value="__('Instagram URL')" />
                                <x-text-input id="instagram_url" name="instagram_url" type="url"
                                              class="mt-1 block w-full" :value="old('instagram_url', $settings['instagram_url'] ?? '')"
                                              placeholder="https://instagram.com/yourhandle" />
                                <x-input-error class="mt-2" :messages="$errors->get('instagram_url')" />
                            </div>

                            <div>
                                <x-input-label for="youtube_url" :value="__('YouTube URL')" />
                                <x-text-input id="youtube_url" name="youtube_url" type="url"
                                              class="mt-1 block w-full" :value="old('youtube_url', $settings['youtube_url'] ?? '')"
                                              placeholder="https://youtube.com/yourchannel" />
                                <x-input-error class="mt-2" :messages="$errors->get('youtube_url')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('Save Settings') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let validationTimeout;

        function onClientIdInput() {
            const validationDiv = document.getElementById('client-id-validation');
            const inputField = document.getElementById('prokerala_client_id');

            validationDiv.innerHTML = '';

            // Reset input field styling
            inputField.classList.remove('border-red-500', 'border-green-500', 'border-blue-500');
            inputField.classList.add('border-gray-300');

            // Clear existing timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }
        }

        function validateClientId() {
            const clientId = document.getElementById('prokerala_client_id').value.trim();
            const validationDiv = document.getElementById('client-id-validation');
            const inputField = document.getElementById('prokerala_client_id');

            // Clear existing timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }

            if (!clientId) {
                validationDiv.innerHTML = '';
                inputField.classList.remove('border-red-500', 'border-green-500', 'border-blue-500');
                inputField.classList.add('border-gray-300');
                return;
            }

            // Check UUID format first
            const uuidPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
            if (!uuidPattern.test(clientId)) {
                validationDiv.innerHTML = '<span class="text-red-600">Invalid format. Must be a valid UUID.</span>';
                inputField.classList.remove('border-gray-300', 'border-green-500', 'border-blue-500');
                inputField.classList.add('border-red-500');
                return;
            }

            // Debounce the API call
            validationTimeout = setTimeout(() => {
                validationDiv.innerHTML = '<span class="text-blue-600">Validating client ID...</span>';
                inputField.classList.remove('border-gray-300', 'border-red-500', 'border-green-500');
                inputField.classList.add('border-blue-500');

                fetch('{{ route("admin.settings.validate-client") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        prokerala_client_id: clientId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => Promise.reject(data));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        validationDiv.innerHTML = '<span class="text-green-600">✓ ' + data.message + '</span>';
                        inputField.classList.remove('border-gray-300', 'border-red-500', 'border-blue-500');
                        inputField.classList.add('border-green-500');
                    } else {
                        validationDiv.innerHTML = '<span class="text-red-600">✗ ' + data.message + '</span>';
                        inputField.classList.remove('border-gray-300', 'border-green-500', 'border-blue-500');
                        inputField.classList.add('border-red-500');
                    }
                })
                .catch(error => {
                    if (error.message) {
                        validationDiv.innerHTML = '<span class="text-red-600">✗ ' + error.message + '</span>';
                    } else {
                        validationDiv.innerHTML = '<span class="text-red-600">✗ Validation failed</span>';
                    }
                    inputField.classList.remove('border-gray-300', 'border-green-500', 'border-blue-500');
                    inputField.classList.add('border-red-500');
                });
            }, 1000); // 1 second delay
        }

        // Validate on page load if there's already a value
        document.addEventListener('DOMContentLoaded', function() {
            const clientId = document.getElementById('prokerala_client_id').value.trim();
            if (clientId) {
                validateClientId();
            }
        });
    </script>
    @endpush
</x-app-layout>
