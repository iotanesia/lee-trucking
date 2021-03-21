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
                <td style="font-family:Calibri;font-size:11pt;text-align:left;">
                    TANGGAL: {{$startDate}} - {{$endDate}}
                </td>
            </tr>
            <tr>
                <td style="font-family:Calibri;font-size:11pt;text-align:left;">
                    TIPE PEMBAYARAN: {{$tipePembayaran}}
                </td>
            </tr>
            <tr>
                <td style="font-family:Calibri;font-size:11pt;text-align:left;">
                    NAMA AKTIVITI: {{$namaAktiviti}}
                </td>
            </tr>
            <br/>
        </Table>
        
        <?php $no = 1; ?>
        @if(!empty($data)) 
        <table style="width:70%">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>No</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Tanggal</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nomor Invoice</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nomor Surat Jalan</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Aktiviti</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:10pt;color:#FFFFFF;vertical-align: middle;"><B>Debit</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Credit</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Bank</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nama Rekening</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Nomor Rekening</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Inputter</B></th>
                    <th style="border: 1px solid #000000; background-color:#92D050; text-align:center;font-family:Calibri;font-size:11pt;color:#FFFFFF;vertical-align: middle;"><B>Source</B></th>
                </tr>
            </thead>
            @foreach ($data as $row)
                <tr>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:5px;vertical-align: middle;">{{$no++}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->created_at}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->nomor_inv}}</td>
                    <td style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->nomor_surat_jalan}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->sheet_name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->nominal_debit}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->nominal_credit}}</td>
                    <td style="border: 1px solid #000000; text-align:left;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;;width:15px;vertical-align: middle;">{{$row->bank_name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->rek_name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:20px;vertical-align: middle;">{{$row->no_rek}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->name}}</td>
                    <td style="border: 1px solid #000000; text-align:center;font-family:Calibri;font-size:8pt;color:#000000;word-wrap:break-word;width:15px;vertical-align: middle;">{{$row->table}}</td>
                </tr>
            @endforeach 
                <tr>
                    <td colspan="5"
                        style="font-family:Calibri;font-size:8pt;text-align:left;"><b>TOTAL</b></td>
                    <td id="total-debit-jurnal" style="font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;"><b>{{$totalDebit}}</b></td>
                    <td id="total-credit-jurnal" style="font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;text-align:center;"><b>{{$totalCredit}}</b></td>
                </tr>
                <tr>
                    <td colspan="5"
                            style="font-family:Calibri;font-size:8pt;text-align:left;"><b>INCOME (PROFIT)</b></td>
                    <td id="income-jurnal" style="font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;"><b>{{$totalIncome}}</b></td>
                </tr>
                <tr>
                    <td colspan="5"
                            style="font-family:Calibri;font-size:8pt;text-align:left;"><b>LOSS</b></td>
                    <td id="loss-jurnal" style="font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;color:#FF0000;border-bottom:2px solid #000000;"><b>{{$totalLoss}}</b></td>
                </tr>
                @if(!($balance == '')) 
                <tr>
                    <td colspan="5"
                    style="background-color:#FFFF00;font-family:Calibri;font-size:8pt;text-align:left;"><b>BALANCE</b></td>
                    <td id="balance-jurnal" style="background-color:#FFFF00;font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;border-bottom:2px solid #000000;"><b>{{$balance}}</b></td>
                </tr>
                
                <tr>
                    <td colspan="5"
                    style="background-color:#92D050;font-family:Calibri;font-size:8pt;text-align:left;"><b>SELISIH</b></td>
                    <td id="total-balance-jurnal" style="background-color:#92D050;font-family:Calibri;font-size:8pt;border-left:2px solid #000000;border-right:2px solid #000000;text-align:center;border-bottom:2px solid #000000;"><b>{{$totalBalance}}</b></td>
                </tr>
                @endif
        </table> 
        @endif 
    </body>
</html>