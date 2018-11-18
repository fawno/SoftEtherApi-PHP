<?php

require('SoftEtherApi\SoftEther.php');
use SoftEtherApi\SoftEther;

$softEther = new SoftEther("hostname", 5555);

$res = $softEther->Connect();
$authRes = $softEther->Authenticate("admin");
echo $authRes->Error;

$status = $softEther->HubApi->GetStatus("hubName");
echo 'done';