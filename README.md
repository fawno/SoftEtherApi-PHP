[![GitHub license](https://img.shields.io/github/license/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/blob/master/LICENSE)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/tags)
[![GitHub release](https://img.shields.io/github/release/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/releases)
[![Packagist](https://img.shields.io/packagist/v/hoppler/softether-api)](https://packagist.org/packages/hoppler/softether-api)
[![Packagist Downloads](https://img.shields.io/packagist/dt/hoppler/softether-api)](https://packagist.org/packages/hoppler/softether-api/stats)
[![GitHub issues](https://img.shields.io/github/issues/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/issues)
[![GitHub forks](https://img.shields.io/github/forks/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/network)
[![GitHub stars](https://img.shields.io/github/stars/hoppler/SoftEtherApi-PHP)](https://github.com/hoppler/SoftEtherApi-PHP/stargazers)

# SoftEtherApi-PHP
SoftEther VPN Api for PHP

There are still some issues as i only use the c# project activly but not this port. Just open a ticket.

For examples please see:
https://github.com/hoppler/SoftEtherApi

## Installation using Composer

You can install this plugin into your application using
[composer](https://getcomposer.org):

```
  composer require hoppler/softether-api
```

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
