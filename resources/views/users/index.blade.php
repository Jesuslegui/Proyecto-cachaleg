@extends('layouts.app')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><div><h1><i class="bi bi-person-gear"></i> Usuarios</h1><p class="text-muted mb-0">Administra las cuentas y sus permisos.</p></div><a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Crear usuario</a></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($users as $user)
<tr><td>{{ $user->name }} @if($user->is(auth()->user()))<span class="badge text-bg-light">Tú</span>@endif</td><td>{{ $user->email }}</td><td><span class="badge text-bg-{{ $user->isAdmin() ? 'primary' : 'secondary' }}">{{ $user->isAdmin() ? 'Administrador' : 'Usuario' }}</span></td><td><span class="badge text-bg-{{ $user->trashed() ? 'secondary' : 'success' }}">{{ $user->trashed() ? 'Inactivo' : 'Activo' }}</span></td><td>
	@if(!$user->trashed())
		<form method="POST" action="{{ route('users.role', $user) }}" class="d-inline-flex gap-2 align-items-center">@csrf @method('PATCH')<select name="role" class="form-select form-select-sm" aria-label="Rol de {{ $user->name }}" @disabled($user->is(auth()->user()))><option value="user" @selected($user->role === 'user')>Usuario</option><option value="admin" @selected($user->role === 'admin')>Administrador</option></select><button class="btn btn-outline-primary btn-sm" @disabled($user->is(auth()->user()))>Guardar rol</button></form>
		<a href="{{ route('users.edit', $user) }}" class="btn btn-outline-secondary btn-sm" title="Editar usuario" aria-label="Editar usuario"><i class="bi bi-pencil"></i></a>
		@if(!$user->is(auth()->user()))<form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" data-confirm="¿Desactivar este usuario?">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" title="Desactivar" aria-label="Desactivar usuario"><i class="bi bi-person-dash"></i></button></form>@endif
	@else
		<form method="POST" action="{{ route('users.restore', $user->id) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-outline-success btn-sm"><i class="bi bi-person-check me-1"></i> Reactivar</button></form>
	@endif
</td></tr>
@empty
<tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td></tr>
@endforelse
</tbody></table></div></div>
@endsection