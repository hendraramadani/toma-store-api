<table class="table table-bordered {{ !$loop->last ? 'page-break' : ''}}">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>