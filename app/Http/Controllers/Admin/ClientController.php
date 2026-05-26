<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->where('role', 'client')->with('assignments.project', 'units.project');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('phone', 'like', '%'.$search.'%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('admin.clients.index', [
            'company' => SiteData::company(),
            'clients' => $query->latest()->get(),
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'client', 404);

        return view('admin.clients.show', [
            'company' => SiteData::company(),
            'client' => $user->load(['assignments.project', 'assignments.unit', 'units.project', 'otps' => fn ($query) => $query->latest()->take(10), 'supportTickets', 'documents']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $client = User::query()->create($data + ['role' => 'client', 'title' => 'Client', 'department' => 'Clients']);
        Activity::log('client.created', 'New client created in admin.', $request->user(), $client);

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'client', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:50', Rule::unique('users', 'phone')->ignore($user->id)],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'bio' => ['nullable', 'string'],
        ]);

        $user->update($data);
        Activity::log('client.updated', 'Client details updated.', $request->user(), $user);

        return back()->with('success', 'Client record updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'client', 404);

        $data = $request->validate([
            'confirm_email' => ['required', 'email'],
        ]);

        if (strtolower($data['confirm_email']) !== strtolower((string) $user->email)) {
            return back()->withErrors([
                'confirm_email' => 'Type the client email exactly to delete this client.',
            ]);
        }

        $user->units()->update(['client_id' => null, 'status' => 'available']);

        Activity::log('client.deleted', 'Client record deleted from admin.', $request->user(), $user);

        $user->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client removed successfully.');
    }
}
