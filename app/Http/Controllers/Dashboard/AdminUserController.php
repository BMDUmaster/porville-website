<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminModules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Main admin only: create sub admins and choose which modules they can use.
 */
class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $staff = User::query()
            ->whereIn('role', ['admin', 'sub_admin'])
            ->orderByRaw("role = 'admin' desc")
            ->orderBy('name')
            ->get();

        $editing = $request->filled('edit')
            ? User::where('role', 'sub_admin')->find($request->integer('edit'))
            : null;

        $modules = AdminModules::MODULES;

        return view('dashboard.admins.index', compact('staff', 'editing', 'modules'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        User::create([
            'name'        => $data['name'],
            'email'       => strtolower($data['email']),
            'phone'       => $data['phone'] ?? null,
            'password'    => Hash::make($data['password']),
            'role'        => 'sub_admin',
            'permissions' => $data['permissions'] ?? [],
            'status'      => $data['status'],
        ]);

        return redirect()->route('dashboard.admins')->with('success', "Sub admin {$data['name']} created.");
    }

    public function update(Request $request, User $staff)
    {
        abort_unless($staff->role === 'sub_admin', 404);

        $data = $this->validated($request, $staff);

        $staff->fill([
            'name'        => $data['name'],
            'email'       => strtolower($data['email']),
            'phone'       => $data['phone'] ?? null,
            'permissions' => $data['permissions'] ?? [],
            'status'      => $data['status'],
        ]);

        if (! empty($data['password'])) {
            $staff->password = Hash::make($data['password']);
        }

        $staff->save();

        return redirect()->route('dashboard.admins')->with('success', "Sub admin {$staff->name} updated.");
    }

    public function destroy(User $staff)
    {
        abort_unless($staff->role === 'sub_admin', 404);

        $staff->delete();

        return redirect()->route('dashboard.admins')->with('success', 'Sub admin removed.');
    }

    private function validated(Request $request, ?User $staff = null): array
    {
        return $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($staff?->id)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'password'      => [$staff ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => [Rule::in(AdminModules::keys())],
            'status'        => ['required', Rule::in(['active', 'blocked'])],
        ], [
            'email.unique' => 'This email is already used by another account.',
        ]);
    }
}
