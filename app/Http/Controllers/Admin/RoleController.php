<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'company' => SiteData::company(),
            'users' => User::query()->orderBy('role')->orderBy('name')->get(),
            'matrix' => SiteData::roleMatrix(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($data);
        Activity::log('role.updated', 'User role or status updated.', $request->user(), $user, $data);

        return back()->with('success', 'User role updated.');
    }
}
