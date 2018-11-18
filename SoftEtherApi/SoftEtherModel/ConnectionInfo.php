<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class ConnectionInfo extends Model\BaseSoftEtherModel
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