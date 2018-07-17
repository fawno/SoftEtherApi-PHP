<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class ServerInfo extends BaseSoftEtherModel
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