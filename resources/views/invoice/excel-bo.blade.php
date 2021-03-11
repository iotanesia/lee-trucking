<html> 
    <body> Total Registered User : {{$total}} Total Registered User With Photo : {{$total_with_photo}} <h1>Data User</h1> 
        @if(!empty($users)) 
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Nama</th>
                <th>Struck</th>
                <th>Email</th>
                <th>Phone</th>
                <th>FB ID</th>
                <th>Timestamp</th>
                <th>Publish</th>
            </tr>
            </thead>
            @foreach ($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td><img src="{{URL::asset('assets/images/upload/' . $row->photo)}}" width="200px"/></td>
                <td>{{$row->nama}}</td>
                <td>{{$row->struck}}</td>
                <td>{{$row->email}}</td>
                <td>{{$row->phone}}</td>
                <td>{{$row->fbid}}</td>
                <td>{{$row->timestamp}}</td>
                <td>{{$row->publish}}</td>
            </tr>
            @endforeach 
        </table> 
        @endif 
    </body>
</html>