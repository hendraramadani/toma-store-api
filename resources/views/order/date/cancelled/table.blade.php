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
            <th>ID</th>
            <th>Kustomer</th>
            <th>Kurir</th>
            <th>Status Pesanan</th>
            <th>Total Bayar</th>
            <th>Tanggal Order</th>
        </tr>
    </thead>
    <tbody  id="table">
        @foreach($data as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->nama }}</td>
            <td>{{ $user->kurir }}</td>
            <td>{{ $user->status }}</td>
            <td>{{ $user->total }}</td>
            <td>{{ $user->dibuat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>