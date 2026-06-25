<x-layouts.admin :company="$company" page-heading="Roles and Permissions">
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="luxury-card-flat overflow-hidden p-0">
            <div class="border-b border-border px-6 py-4">
                <h2 class="font-display text-base font-semibold">Staff Accounts</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table-shell">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Current Role</th>
                            <th>Status</th>
                            <th>Update Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="table-row-shell">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="font-medium">{{ $user->name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="highlight-chip">{{ str($user->role)->replace('_', ' ') }}</span>
                                </td>
                                <td><x-status-badge :value="$user->status" /></td>
                                <td>
                                    <form action="{{ route('admin.roles.update', $user) }}" method="POST" class="grid gap-2 min-w-[280px] md:grid-cols-2">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="admin-label">Role</label>
                                            <select name="role" class="input-shell text-xs">
                                                @foreach (['admin', 'editor', 'project_manager', 'finance', 'content_manager'] as $role)
                                                    <option value="{{ $role }}" @selected($user->role === $role)>{{ str($role)->replace('_', ' ')->title() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="admin-label">Status</label>
                                            <select name="status" class="input-shell text-xs">
                                                <option value="active" @selected($user->status === 'active')>Active</option>
                                                <option value="inactive" @selected($user->status === 'inactive')>Inactive</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="admin-label">Title</label>
                                            <input name="title" value="{{ $user->title }}" class="input-shell text-xs">
                                        </div>
                                        <div>
                                            <label class="admin-label">Department</label>
                                            <input name="department" value="{{ $user->department }}" class="input-shell text-xs">
                                        </div>
                                        <button class="luxury-btn-primary md:col-span-2 text-xs">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="luxury-card-flat overflow-hidden p-0">
            <div class="border-b border-border px-6 py-4">
                <h2 class="font-display text-base font-semibold">Permission Matrix</h2>
            </div>
            <div class="divide-y divide-border/50 p-6 space-y-6">
                @foreach ($matrix as $role => $permissions)
                    <div class="{{ $loop->first ? '' : 'pt-4' }}">
                        <span class="highlight-chip mb-3 inline-block">{{ str($role)->replace('_', ' ') }}</span>
                        <div class="space-y-1.5">
                            @foreach ($permissions as $permission)
                                <div class="check-item">
                                    <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-sm text-muted-foreground">{{ $permission }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>
