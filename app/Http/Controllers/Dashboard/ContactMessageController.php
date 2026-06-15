<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        [$messages, $stats] = $this->messageList($request);

        return view('dashboard.contact-messages.index', compact('messages', 'stats'));
    }

    public function live(Request $request)
    {
        [$messages, $stats] = $this->messageList($request);

        return response()->json([
            'total' => $stats['total'],
            'unread' => $stats['unread'],
            'rows' => view('dashboard.contact-messages.partials.rows', compact('messages'))->render(),
            'pagination' => $messages->withQueryString()->links()->render(),
        ]);
    }

    public function show(ContactMessage $message)
    {
        if (! $message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return view('dashboard.contact-messages.show', compact('message'));
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['read_at' => now()]);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('dashboard.contact-messages')->with('success', 'Message deleted.');
    }

    private function messageList(Request $request): array
    {
        $query = ContactMessage::query()->latest();

        if ($request->filled('search')) {
            $query->where(function ($messageQuery) use ($request) {
                $messageQuery
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'unread') {
            $query->unread();
        }

        $messages = $query->paginate(15)->withPath(route('dashboard.contact-messages'));
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
        ];

        return [$messages, $stats];
    }
}
