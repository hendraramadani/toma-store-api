<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>



<script type="text/javascript">
    $("#table td").each(function() {
    if (this.textContent == null) this.textContent = "-"
})
</script>

<table class="table table-bordered {{ !$loop->last ? 'page-break' : ''}}">
    <thead>
        <tr>
            <th>Id</th>
            <th>Toko</th>
            <th>No. HP</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody  id="table">
        @foreach($data as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>