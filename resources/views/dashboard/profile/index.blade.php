@extends('layouts.dashboard')
@section('title', 'Profile')
@section('page_title', 'Profile Settings')

@section('content')
<div class="p-4 lg:p-6 max-w-3xl">

    {{-- Profile Header --}}
    <div class="bg-gradient-to-r from-mayview-blue to-mayview-accent text-white p-6 rounded-2xl mb-6 flex items-center gap-4">
        <div class="relative">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center overflow-hidden text-3xl font-bold">
                @if($user->photo ?? false)
                    <img src="{{ asset('storage/'.$user->photo) }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <p class="text-sm opacity-90 capitalize">{{ $user->role }}</p>
            <p class="text-sm opacity-75">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Update Profile --}}
    <div class="bg-white p-5 rounded-2xl shadow mb-6">
        <h2 class="font-semibold text-lg mb-4">Update Profile</h2>
        <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="{{ $user->phone ?? '' }}"
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Profile Photo</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full border px-4 py-2 rounded-lg text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700">
                Update Profile
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white p-5 rounded-2xl shadow">
        <h2 class="font-semibold text-lg mb-4">Change Password</h2>
        <form method="POST" action="{{ route('dashboard.profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">New Password</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="bg-red-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-red-600">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection
