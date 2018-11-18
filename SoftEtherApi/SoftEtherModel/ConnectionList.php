<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class ConnectionList extends Model\BaseSoftEtherModel
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