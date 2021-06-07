<h1>Registration Form</h1>
<form action="update" method="post">
Id:<input type="id" name="id" value= {{ $data['id'] }} ><br><br>
Name:<input type="text" name="name" value= {{ $data['name'] }}><br><br>
Email:<input type="email" name="email" value= {{ $data['email'] }}><br><br>
Address:<input type="text" name="address" value= {{ $data['address'] }}><br><br>
Mobile:<input type="tel" maxlength="10" name="mobile" value= {{ $data['mobile'] }}><br><br>
Password:<input type="password" name="password" value= {{ $data['password'] }}><br><br>
<input type="submit" name="Update"><br><br>
</form>