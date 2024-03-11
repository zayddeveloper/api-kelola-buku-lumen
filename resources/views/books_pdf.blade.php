/**
* Created by Sa'id on 11/03/2024
*/

<!doctype html>
<html>
<head>
    <title>Membuat Laporan PDF Dengan DOMPDF Laravel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }

    </style>
    <center>
        <h5>Membuat Laporan PDF Dengan DOMPDF Lumen</h4>
    </center>
    <br></br>

    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>judul</th>
                <th>penulis</th>
                <th>penerbit</th>
                <th>tahun terbit</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($data as $p)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$p->judul}}</td>
                <td>{{$p->penulis}}</td>
                <td>{{$p->penerbit}}</td>
                <td>{{$p->tahun_terbit}}</td>
                <td>{{$p->user->nama}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
