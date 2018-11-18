<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubUserList extends Model\BaseSoftEtherModel
    {
        public $AuthType;
        public $DenyAccess;
        public $Expires;
        public $ExRecvBroadcastBytes;
        public $ExRecvBroadcastCount;
        public $ExRecvUnicastBytes;
        public $ExRecvUnicastCount;
        public $ExSendBroadcastBytes;
        public $ExSendBroadcastCount;
        public $ExSendUnicastBytes;
        public $ExSendUnicastCount;
        public $GroupName;
        public $HubName;
        public $IsExpiresFilled;
        public $IsTrafficFilled;
        public $LastLoginTime;
        public $Name;
        public $Note;
        public $NumLogin;
        public $Realname;
    }
}