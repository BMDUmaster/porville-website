<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('recipient')->latest();

        if ($request->filled('search')) {
            $query->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
        }

        $notifications = $query->paginate(20);
        $users = User::query()
            ->where('role', 'customer')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('dashboard.notifications.index', compact('notifications', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $validUserIds = User::query()
            ->whereIn('id', $data['user_ids'])
            ->where('role', 'customer')
            ->where('status', 'active')
            ->pluck('id');

        if ($validUserIds->isEmpty()) {
            return back()->withInput()->withErrors([
                'user_ids' => 'Please select at least one active customer.',
            ]);
        }

        $validUserIds->each(function ($userId) use ($data) {
            Notification::create([
                'subject' => $data['subject'],
                'message' => $data['message'],
                'sent_by' => auth()->id(),
                'recipient_id' => $userId,
            ]);
        });

        return back()->with('success', 'Notification sent to selected customer(s).');
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);

        $notification->update($data);
        return back()->with('success', 'Notification updated.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }
}
