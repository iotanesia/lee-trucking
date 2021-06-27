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
                Laporan Ekspedisi dan Rit {{$param}} Tanggal {{$startDate}} - {{$endDate}}
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
                <td style="color:#ffffff;background-color:#4bb1b1;font-weight: bold;font-family:Calibri;font-size:11pt;text-align:left;">
                   Bonus Rit {{$row->param}} : {{$row->paramName}}
                </td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;">Total OJK {{$row->total_ojk}}</td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;">Total OTV {{$row->total_otv}}</td>
               
            </tr>
            <tr>
                <td style="color:#ffffff;background-color:#4bb1b1;font-weight: bold;font-family:Calibri;font-size:11pt;text-align:left;">
                    Total Ekspedisi : {{$row->total_ekspedisi}}
                </td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="color:#ffffff;background-color:#4bb1b1;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
        </Table>
        <table style="width:70%">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>No</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nomor Surat Jalan</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nomor Invoice</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Driver</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tanggal Invoice</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>Tanggal PO</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tujuan</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Status</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Harga OJK</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Harga OTV</B></th>
                </tr>
            </thead>
            
                <?php $no = 1; ?>
                @foreach ($row->detail as $rows)
                <tr>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$no++}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->nomor_surat_jalan}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$rows->nomor_inv}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->driver_name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->tgl_inv}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$rows->tgl_po}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:25px;vertical-align: middle;">{{$rows->tujuan}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:23px;vertical-align: middle;"><span style="color:{{$rows->textColor}} !important;background-color:{{$rows->backgroundColor}} !important"> {{$rows->status_name}} {{$rows->payment}}</span></td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:25px;vertical-align: middle;">{{$rows->harga_ojk}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:25px;vertical-align: middle;">{{$rows->harga_otv}}</td>
                </tr>
                @endforeach
        </table> 
        @endforeach 
        @endif 
    </body>
</html>