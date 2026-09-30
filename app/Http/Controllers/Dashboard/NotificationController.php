<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('recipient')->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            if ($search !== '') {
                $query->where(function ($notificationQuery) use ($search) {
                    $notificationQuery
                        ->where('subject', 'like', '%' . $search . '%')
                        ->orWhere('message', 'like', '%' . $search . '%')
                        ->orWhereHas('recipient', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('id', $search);
                        });
                });
            }
        }

        $notifications = $query->paginate(20);
        $customerCount = $this->activeCustomers()->count();

        return view('dashboard.notifications.index', compact('notifications', 'customerCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);

        // Admin notifications always go to every active customer.
        $recipients = $this->activeCustomers()->get(['id', 'name', 'email']);

        if ($recipients->isEmpty()) {
            return back()->withInput()->with('error', 'There are no active customers to notify yet.');
        }

        $recipients->each(function (User $recipient) use ($data) {
            Notification::create([
                'subject' => $data['subject'],
                'message' => $data['message'],
                'sent_by' => auth()->id(),
                'recipient_id' => $recipient->id,
            ]);

            if ($recipient->email) {
                try {
                    Mail::raw($data['message'], function ($mail) use ($recipient, $data) {
                        $mail->to($recipient->email)->subject($data['subject']);
                    });
                } catch (Throwable $e) {
                    Log::error('Admin notification email failed for user ' . $recipient->id . ': ' . $e->getMessage());
                }
            }
        });

        return back()->with('success', 'Notification sent to all ' . $recipients->count() . ' customer(s) — in-app and email.');
    }

    private function activeCustomers()
    {
        return User::query()
            ->where('role', 'customer')
            ->where('status', 'active');
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
