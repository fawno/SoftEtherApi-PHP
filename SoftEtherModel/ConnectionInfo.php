<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class ConnectionInfo extends BaseSoftEtherModel
    {
        public $ClientBuild;
        public $ClientStr;
        public $ClientVer;
        public $ConnectedTime;
        public $Hostname;
        public $Ip;
        public $Ip_ipv6_array;
        public $Ip_ipv6_bool;
        public $Ip_ipv6_scope_id;
        public $Name;
        public $Port;
        public $ServerBuild;
        public $ServerStr;
        public $ServerVer;
        public $Type;
    }
}