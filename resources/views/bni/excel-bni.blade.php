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
        <Table>
            <tr>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                    LAPORAN DATA SL
                </td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                </td>  
                <td style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-top: 2px solid #000000;border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
            <br/>
            
            <tr>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="font-family:Calibri;font-size:11pt;text-align:left;">
                </td>
                <td style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
              
            </tr>
            <tr> 
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                    PER TANGGAL: {{$startDate}} - {{$endDate}}
                </td> 
                <td style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td style="border-bottom: 2px solid #000000;border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
        </Table>
        
        <?php $no = 1; ?>
        @if(!empty($data)) 
        <table style="width:70%">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>No</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Wil</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Unit</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Produk</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>CIF</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>No Rek</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Nasabah</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Kol</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Maks Krd</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>BK Debit</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Retrukturasi</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Flag Covid</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Desk Flag Covid</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Flag</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Dates</B></th>
                </tr>
            </thead>
            @foreach ($data as $row)
                <tr>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$no++}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->wil}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->unit}}</td>
                    <td style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->produk}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;vertical-align: middle;">{{$row->cif}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:10px;vertical-align: middle;">{{$row->no_rek}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$row->nama_nasabah}}</td>
                    <td style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:23px;vertical-align: middle;">{{$row->kol}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->MaksKrd}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->bk_debit}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->restrukturisasi}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->flag_covid}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->desk_flag_covid}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->flag}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->dates}}</td>
                </tr>
            @endforeach
        </table> 
        @endif 
    </body>
</html>