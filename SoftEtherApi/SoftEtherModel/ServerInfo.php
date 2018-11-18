<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class ServerInfo extends Model\BaseSoftEtherModel
    {
        public $KernelName;
        public $OsProductName;
        public $OsServicePack;
        public $OsSystemName;
        public $OsType;
        public $OsVendorName;
        public $OsVersion;
        public $ServerBuildDate;
        public $ServerBuildInfoString;
        public $ServerBuildInt;
        public $ServerFamilyName;
        public $ServerHostName;
        public $ServerProductName;
        public $ServerType;
        public $ServerVerInt;
        public $ServerVersionString;
    }
}