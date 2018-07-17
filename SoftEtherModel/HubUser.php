<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class HubUser extends BaseSoftEtherModel
    {
        public $AuthType;
        public $CreatedTime;
        public $ExpireTime;
        public $GroupName;
        public $HashedKey;
        public $HubName;
        public $Name;
        public $Note;
        public $NtLmSecureHash;
        public $NumLogin;
        public $Realname;
        public $RecvBroadcastBytes;
        public $RecvBroadcastCount;
        public $RecvUnicastBytes;
        public $RecvUnicastCount;
        public $SendBroadcastBytes;
        public $SendBroadcastCount;
        public $SendUnicastBytes;
        public $SendUnicastCount;
        public $UpdatedTime;
    }
}