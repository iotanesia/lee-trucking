<html> 
    <head>
    </head>
    <body>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        @if(!empty($data)) 
        <Table>
            <tr>  
                <td style="font-weight: bold;font-family:Calibri;font-size:11pt;text-align:left;">
                    @if(isset($startDate) && isset($endDate))
                    Laporan Perbaikan Truck Tanggal {{$startDate}} - {{$endDate}}
                    @else
                    Laporan Perbaikan Truck
                    @endif
                </td>
                <td style="font-family:Calibri;font-size:11pt;text-align:left;">
                </td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
              
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
        </Table>
        {{-- {{dd($data)}} --}}
        @foreach ($data as $row)
        <Table>
            <tr>
                <td style="color:#ffffff;background-color:#4bb1b1;font-weight: bold;font-family:Calibri;font-size:10pt;text-align:left;">
                  Kode Repair : {{$row->kode_repair}}
                </td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
            <tr>
                <td style="color:#ffffff;background-color:#4bb1b1;font-weight: bold;font-family:Calibri;font-size:10pt;text-align:left;">
                    Nama Truck : {{$row->truck_name}}
                </td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
            <tr>
                <td style="color:#ffffff;background-color:#4bb1b1;font-weight: bold;font-family:Calibri;font-size:10pt;text-align:left;">
                    Nomor Plat : {{$row->truck_plat}}
                </td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
        </Table>
        <table style="width:70%">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>No</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tanggal</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Sparepart</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Barcode Gudang</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Barcode Pabrik</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>Tipe Sparepart</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Jumlah Stok</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Amount</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Total</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tipe Satuan</B></th>
                </tr>
            </thead>
            
                <?php $no = 1; ?>
                @foreach ($row->dataDetail as $rows)
                <tr>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$no++}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->created_at}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->sparepart_name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->barcode_gudang}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->barcode_pabrik}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->sparepart_type}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$rows->jumlah_stok}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:25px;vertical-align: middle;">{{$rows->amount}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:25px;vertical-align: middle;">{{$rows->total}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$rows->satuan_type}}</td>
                </tr>
                @endforeach
        </table> 
        @endforeach 
        @endif 
    </body>
</html>