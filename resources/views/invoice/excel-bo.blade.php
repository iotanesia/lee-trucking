<html> 
    <body> Invoice Report BA Tanggal <h1>{{$startDate}} - {{$endDate}}</h1> 
        {{$no = 1;}}
        @if(!empty($data)) 
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Surat Jalan</th>
                    <th>Tujuan</th>
                    <th>Plat</th>
                    <th>Qty Palet</th>
                    <th>Rit</th>
                    <th>Nama Toko</th>
                    <th>Harga/Rit</th>
                    <th>Total</th>
                </tr>
            </thead>
            @foreach ($data as $row)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$row->tgl_po}}</td>
                <td>{{$row->nomor_surat_jalan}}</td>
                <td>{{$row->kabupaten}}</td>
                <td>{{$row->truck_plat}}</td>
                <td>{{$row->jumlah_palet}}</td>
                <td>{{$row->rit}}</td>
                <td>{{$row->toko}}</td>
                <td>{{$row->harga_per_rit}}</td>
                <td>{{$row->total}}</td>
            </tr>
            @endforeach 
        </table> 
        @endif 
    </body>
</html>