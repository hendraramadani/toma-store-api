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
            <th>Produk</th>
            <th>Toko</th>
            <th>Deskripsi</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody  id="table">
        @foreach($data as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->store_name }}</td>
            <td>{{ $user->description }}</td>
            <td>
                <?php
                $rupiah=number_format($user->cost,2,',','.');
                echo 'Rp '.$rupiah;
                ?>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>