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
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                    KEPADA: {{$namaPt}}
                </td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                     NO INVOICE : {{$noInvoice}}
                </td>  
                <td width="20" style="border-top: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-top: 2px solid #000000;border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
            </tr>
            <br/>
            
            <tr>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="font-family:Calibri;font-size:11pt;text-align:left;">
                    TANGGAL INVOICE: {{$tglInvoice}}
                </td>
                <td width="20" style="font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
              
            </tr>
            <tr> 
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:11pt;text-align:left;">
                    BULAN INVOICE: {{$startDate}} - {{$endDate}}
                </td> 
                <td width="20" style="border-bottom: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
                <td width="20" style="border-bottom: 2px solid #000000;border-right: 2px solid #000000;font-family:Calibri;font-size:10pt;text-align:left;"></td>
               
            </tr>
        </Table>
        
        <?php $no = 1; ?>
        @if(!empty($data)) 
        <table style="width:70%">
            <thead>
                <tr>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>No</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tanggal</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Surat Jalan</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tujuan</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Plat</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>Qty<br/>Palet</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Rit</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Toko</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Harga/Rit</B></th>
                    <th width="20" style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Total</B></th>
                </tr>
            </thead>
            @foreach ($data as $row)
                <tr>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$no++}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->tgl_po}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->nomor_inv}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->kabupaten}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;vertical-align: middle;">{{$row->truck_plat}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:10px;vertical-align: middle;">{{$row->jumlah_palet}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$row->rit}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:23px;vertical-align: middle;">{{$row->toko}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->harga_per_rit}}</td>
                    <td width="20" style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->total}}</td>
                </tr>
            @endforeach 
                <tr>
                    <td width="20" colspan="9"
                        style="font-family:Calibri;font-size:8pt;text-align:center;"><b>TOTAL INVOICE {{$month}} {{$year}}</b></td>
                    <td width="20" id="total-invoice-bo" style="font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;"><b>{{$totalInv}}</b></td>
                </tr>
                <tr>
                    <td width="20" colspan="9"
                            style="font-family:Calibri;font-size:8pt;text-align:center;"><b>PPN 10%</b></td>
                    <td width="20" id="ppn-10-bo" style="font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;"><b>{{$ppn10}}</b></td>
                </tr>
                <tr>
                    <td width="20" colspan="9"
                            style="font-family:Calibri;font-size:8pt;text-align:center;"><b>PPH 23</b></td>
                    <td width="20" id="pph-23-bo" style="font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;color:#FF0000;"><b>{{$pph23}}</b></td>
                </tr>
                <tr>
                    <td width="20" colspan="9" style="font-family:Calibri;font-size:8pt;text-align:center;"><b>TOTAL KESELURUHAN INVOICE</b></td>
                    <td width="20" id="total-keseluruhan-bo" style="font-family:Calibri;font-size:8pt;border:2px solid #000000;text-align:center;background-color:#FFFF00"><b>{{$totalKeseluruhan}}</b></td> 
                </tr>
                <tr>

                </tr>
                <tr>
                    <td width="20" colspan="3" style="font-family:Calibri;font-size:8pt;text-align:left;">
                        <B>MOHON DI TRANSFER KE REKENNG:</B>
                    </td>
                </tr>
                <tr>
                    <td width="20" colspan="3" style="font-family:Calibri;background-color:#FFFF00;font-size:8pt;text-align:left;">
                        <B>7005818818 BANK BCA</B>
                    </td>
                </tr>
                <tr>
                    <td width="20" colspan="3" style="font-family:Calibri;background-color:#FFFF00;font-size:8pt;text-align:left;">
                        <B>ATAS NAMA: PT. TRANS SINDUR JAYA</B>
                    </td>
                </tr>
        </table> 
        @endif 
    </body>
</html>