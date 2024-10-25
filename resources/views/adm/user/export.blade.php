<table>
    <thead>
    <tr>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Departamento</th>
        <th>Agencia</th>
        <th>Perfil de Usuario</th>
        <th>Permisos</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->username }}</td>
            <td>{{ $user->full_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->city instanceof \Sibas\Entities\City ? $user->city->name : '' }}</td>
            <td>{{ $user->agency instanceof \Sibas\Entities\Agency ? $user->agency->name : '' }}</td>
            <td>{{ $user->profile->first()->name }}</td>
            <td>{{ $user->permissions->implode('name', ', ') }}</td>
            <td>{{ $user->active ? 'Habilitado' : 'Deshabilitado' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>