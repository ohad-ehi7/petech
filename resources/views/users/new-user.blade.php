<x-header :title="'Add New User'">
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Add New User</h1>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role_id" id="role_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->RoleID }}" {{ old('role_id') == $role->RoleID ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-header>
