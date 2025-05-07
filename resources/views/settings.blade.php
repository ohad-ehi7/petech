    <x-header>
        <div class="min-h-screen bg-[#f7f7fa] dark:bg-[#18181c] p-4 sm:p-8">
            <!-- Breadcrumb and Page Header -->
            <div class="max-w-4xl mx-auto mb-8">
                <nav class="mb-2">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="home" class="text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC] transition-colors duration-200">
                                Dashboard
                            </a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="text-[#706f6c] dark:text-[#A1A09A]">/</span>
                            <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Settings</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Account Settings</h1>
                <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">Manage your account settings and preferences</p>
            </div>

            <!-- Settings Card -->
            <div class="max-w-4xl mx-auto bg-white dark:bg-[#23232b] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-md px-6 py-8 md:px-12 md:py-12">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="mb-4 p-4 text-sm text-[#1b1b18] bg-[#FDFDFC] dark:bg-[#23232b] dark:text-[#EDEDEC] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 text-sm text-[#F53003] bg-[#fff2f2] dark:bg-[#2a1a1a] dark:text-[#F61500] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-start">
                        <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Settings Form -->
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" 
                                    class="block w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#18181c] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] focus:ring-0 px-4 py-2"
                                    placeholder="Enter your full name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" 
                                    class="block w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#18181c] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] focus:ring-0 px-4 py-2"
                                    placeholder="Enter your email address">
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Security</h2>
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" 
                                        class="block w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#18181c] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] focus:ring-0 px-4 py-2 pr-10"
                                        placeholder="Enter your current password">
                                    <button type="button" tabindex="-1" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none show-hide-password" data-target="current_password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="new_password" 
                                            class="block w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#18181c] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] focus:ring-0 px-4 py-2 pr-10"
                                            placeholder="Enter new password">
                                        <button type="button" tabindex="-1" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none show-hide-password" data-target="new_password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                            class="block w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#18181c] text-[#1b1b18] dark:text-[#EDEDEC] shadow-sm focus:border-[#1b1b18] dark:focus:border-[#EDEDEC] focus:ring-0 px-4 py-2 pr-10"
                                            placeholder="Confirm new password">
                                        <button type="button" tabindex="-1" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none show-hide-password" data-target="new_password_confirmation">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-2">
                        <a href="home" class="inline-flex justify-center py-2 px-5 border border-[#e3e3e0] dark:border-[#3E3E3A] text-sm font-medium rounded-md text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#23232b] transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-5 border border-[#1b1b18] dark:border-[#EDEDEC] text-sm font-medium rounded-md text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#23232b] hover:bg-[#1b1b18] dark:hover:bg-[#EDEDEC] hover:text-white dark:hover:text-[#1b1b18] transition-colors duration-200">
                            Save Changes
                        </button>
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