<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard - User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add New User Form Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 mb-6">
                <h3 class="text-lg font-bold mb-4">Add New User</h3>
                <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" required class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 border">
                            <option value="student">Student</option>
                            <option value="librarian">Librarian</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" style="background-color: #dc2626;" class="w-full text-white font-medium py-2 px-4 rounded-md text-sm hover:opacity-90 shadow focus:outline-none">
                            + Add User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Users List Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h1 class="text-2xl font-bold text-red-600 mb-2">Welcome Admin!</h1>
                <p class="mb-6 text-gray-600">You are logged in as an Administrator.</p>

                <h3 class="text-lg font-bold mb-4">All Registered Users</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold capitalize">{{ $user->role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <!-- Update Role Form -->
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="flex items-center space-x-1">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="text-sm border-gray-300 rounded-md p-1 border">
                                                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                                    <option value="librarian" {{ $user->role === 'librarian' ? 'selected' : '' }}>Librarian</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="submit" style="background-color: #2563eb;" class="px-3 py-1.5 text-white rounded text-xs font-semibold hover:opacity-90">
                                                    Update
                                                </button>
                                            </form>

                                            <!-- Delete User Form -->
                                            @if(auth()->user()->id !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background-color: #dc2626;" class="px-3 py-1.5 text-white rounded text-xs font-semibold hover:opacity-90">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>