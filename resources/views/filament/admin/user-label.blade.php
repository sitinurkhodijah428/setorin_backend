@php
    $user = auth()->user();
    $role = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : null;
    $roleLabel = ['admin' => 'Administrator', 'petugas' => 'Petugas', 'nasabah' => 'Nasabah'][$role] ?? ucfirst((string) $role);
@endphp

<div class="sj-user">
    <div class="sj-user-name">{{ $user->nama ?? $user->name ?? 'Admin' }}</div>
    <div class="sj-user-role">{{ $roleLabel }}</div>
</div>