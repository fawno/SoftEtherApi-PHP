# SoftEtherApi-PHP
SoftEther VPN Api for PHP

There are still some issues as i only use the c# project activly but not this port. Just open a ticket.

For examples please see:
https://github.com/hoppler/SoftEtherApi

# How to translate the examples
The C# code

```c#
var connectResult = softEther.Connect();
var authResult = softEther.Authenticate(pw);

var user = softEther.HubApi.CreateUser("testHub", "testUser", "userPw");
Console.WriteLine(user.Valid() ? "Success" : user.Error.ToString());
```

The corresponding PHP code

```php
$connectResult = $softEther->Connect();
$authResult = $softEther->Authenticate(pw);

$user = $softEther->HubApi->CreateUser("testHub", "testUser", "userPw");
echo $user->Valid() ? "Success" : $user->Error;
```
