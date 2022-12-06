<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubStatus extends Model\BaseSoftEtherModel
    {
        public $CreatedTime;
        public $HubName;
        public $HubType;
        public $LastCommTime;
        public $LastLoginTime;
        public $NumAccessLists;
        public $NumGroups;
        public $NumIpTables;
        public $NumLogin;
        public $NumMacTables;
        public $NumSessions;
        public $NumSessionsBridge;
        public $NumSessionsClient;
        public $NumUsers;
        public $Online;
        public $RecvBroadcastBytes;
        public $RecvBroadcastCount;
        public $RecvUnicastBytes;
        public $RecvUnicastCount;
        public $SecureNATEnabled;
        public $SendBroadcastBytes;
        public $SendBroadcastCount;
        public $SendUnicastBytes;
        public $SendUnicastCount;
    }
}