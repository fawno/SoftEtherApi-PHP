<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class ConnectionList extends BaseSoftEtherModel
    {
        public $ConnectedTime;
        public $Hostname;
        public $Ip;
        public $Ip_ipv6_array;
        public $Ip_ipv6_bool;
        public $Ip_ipv6_scope_id;
        public $Name;
        public $Port;
        public $Type;
    }
}