@forelse($messages as $index => $message)
    <tr class="transition hover:bg-gray-50">
        <td class="px-4 py-3 text-sm text-gray-500">{{ $messages->firstItem() + $index }}</td>
        <td class="px-4 py-3">
            <p class="text-sm font-bold text-slate-900">{{ $message->name }}</p>
            <a href="mailto:{{ $message->email }}" class="text-xs font-semibold text-blue-600">{{ $message->email }}</a>
        </td>
        <td class="px-4 py-3 text-sm font-semibold text-slate-700">{{ $message->department }}</td>
        <td class="px-4 py-3">
            <p class="max-w-[280px] truncate text-sm text-slate-500">{{ $message->message }}</p>
        </td>
        <td class="px-4 py-3 text-sm text-slate-500">{{ $message->created_at->format('d M Y, h:i A') }}</td>
        <td class="px-4 py-3">
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $message->read_at ? 'bg-slate-100 text-slate-500' : 'bg-green-100 text-green-700' }}">
                {{ $message->read_at ? 'Read' : 'Unread' }}
            </span>
        </td>
        <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
                <a href="{{ route('dashboard.contact-messages.show', $message) }}"
                   class="rounded-lg p-2 text-xs text-blue-600 hover:bg-blue-100">
                    <i class="fa-regular fa-eye"></i>
                </a>
                @unless($message->read_at)
                    <form method="POST" action="{{ route('dashboard.contact-messages.read', $message) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="rounded-lg p-2 text-xs text-green-600 hover:bg-green-100">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                @endunless
                <form method="POST" action="{{ route('dashboard.contact-messages.destroy', $message) }}"
                      onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-lg p-2 text-xs text-red-500 hover:bg-red-100">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-10 text-center text-gray-400">No contact messages found.</td>
    </tr>
@endforelse
