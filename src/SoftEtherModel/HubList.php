<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubList extends Model\BaseSoftEtherModel
    {
        public $CreatedTime;
        public $ExRecvBroadcastBytes;
        public $ExRecvBroadcastCount;
        public $ExRecvUnicastBytes;
        public $ExRecvUnicastCount;
        public $ExSendBroadcastBytes;
        public $ExSendBroadcastCount;
        public $ExSendUnicastBytes;
        public $ExSendUnicastCount;
        public $HubName;
        public $HubType;
        public $IsTrafficFilled;
        public $LastCommTime;
        public $LastLoginTime;
        public $NumGroups;
        public $NumIpTables;
        public $NumLogin;
        public $NumMacTables;
        public $NumSessions;
        public $NumUsers;
        public $Online;
    }
}