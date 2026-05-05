@extends('layouts.app')
@section('title', $user->name)
@section('page-title', 'User Profile — ' . $user->name)

@section('content')
<div class="py-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-center mb-5">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">👤</div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                @php $rc=['admin'=>'bg-red-100 text-red-700','staff'=>'bg-yellow-100 text-yellow-700','user'=>'bg-blue-100 text-blue-700']; @endphp
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $rc[$user->role] }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Student ID</span>
                    <span class="font-medium">{{ $user->student_id ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined</span>
                    <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Active Loans</span>
                    <span class="font-medium text-blue-600">{{ $user->active_borrowings_count }}</span>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium">Edit</a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 text-center border py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Back</a>
            </div>
        </div>

        {{-- Borrowing History --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-700 mb-4">📋 Borrowing History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Book</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Borrowed</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Due</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Returned</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Status</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Fine</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($borrowings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">{{ Str::limit($b->book->title, 25) }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $b->due_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-2">
                                @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$b->status] }}">{{ ucfirst($b->status) }}</span>
                            </td>
                            <td class="px-4 py-2 text-gray-700">{{ $b->fine_amount > 0 ? '₱'.$b->fine_amount : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-400">No borrowing history.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $borrowings->links() }}</div>
        </div>
    </div>
</div>
@endsection