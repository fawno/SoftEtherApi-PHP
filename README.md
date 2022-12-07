[![GitHub license](https://img.shields.io/github/license/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/blob/master/LICENSE)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/tags)
[![GitHub release](https://img.shields.io/github/release/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/releases)
[![Packagist](https://img.shields.io/packagist/v/fawno/softether-api)](https://packagist.org/packages/fawno/softether-api)
[![Packagist Downloads](https://img.shields.io/packagist/dt/fawno/softether-api)](https://packagist.org/packages/fawno/softether-api/stats)
[![GitHub issues](https://img.shields.io/github/issues/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/issues)
[![GitHub forks](https://img.shields.io/github/forks/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/network)
[![GitHub stars](https://img.shields.io/github/stars/fawno/SoftEtherApi-PHP)](https://github.com/fawno/SoftEtherApi-PHP/stargazers)

# SoftEtherApi-PHP
SoftEther VPN Api for PHP

There are still some issues as i only use the c# project activly but not this port. Just open a ticket.

For C# examples please see:
https://github.com/connLAN/SoftEtherApi

## Installation using Composer

You can install this plugin into your application using
[composer](https://getcomposer.org):

```
  composer require fawno/softether-api:@stable
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
