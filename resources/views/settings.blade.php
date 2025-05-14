    <x-header :title="'Settings'">
        <div class="min-h-screen bg-gray-50 p-4 sm:p-8">
            <!-- Breadcrumb and Page Header -->
            <div class="max-w-4xl mx-auto mb-8">
                <nav class="mb-4">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="home" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                Dashboard
                            </a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="text-gray-400">/</span>
                            <span class="text-gray-700 font-medium">Settings</span>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
                        <p class="mt-2 text-sm text-gray-600">Manage your account settings and preferences</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Last updated: {{ auth()->user()->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="m-6 p-4 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="m-6 p-4 text-sm text-red-700 bg-red-50 rounded-lg border border-red-200 flex items-start">
                        <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Settings Form -->
                <form action="{{ route('profile.update') }}" method="POST" class="divide-y divide-gray-200">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center mb-6">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5"
                                    placeholder="Enter your full name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5"
                                    placeholder="Enter your email address">
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center mb-6">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-lock text-blue-600"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Security</h2>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10"
                                        placeholder="Enter your current password">
                                    <button type="button" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none show-hide-password" data-target="current_password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="new_password" 
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10"
                                            placeholder="Enter new password">
                                        <button type="button" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none show-hide-password" data-target="new_password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 pr-10"
                                            placeholder="Confirm new password">
                                        <button type="button" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none show-hide-password" data-target="new_password_confirmation">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                        <div class="flex justify-end space-x-3">
                            <a href="home" class="inline-flex justify-center py-2.5 px-5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="inline-flex justify-center py-2.5 px-5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.querySelectorAll('.show-hide-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = btn.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        </script>
    </x-header>