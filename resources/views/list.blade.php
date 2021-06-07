<table border="1">
<tr>
<th>Id</th>
<th>Name</th>
<th>Email</th>
<th>Address</th>
<th>Mobile</th>
<th>Password</th>
<th>Operation</th>
<th>Edit</th>
</tr>
@foreach($data as $d)
<tr>
<td>{{$d['id']}}</td>
<td>{{$d['name']}}</td>
<td>{{$d['email']}}</td>
<td>{{$d['address']}}</td>
<td>{{$d['mobile']}}</td>
<td>{{$d['password']}}</td>
<td><a href={{ "delete/" .$d['id'] }}


</tr>
</table>