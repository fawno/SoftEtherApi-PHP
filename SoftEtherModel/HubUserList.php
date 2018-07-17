<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class HubUserList extends BaseSoftEtherModel
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

        //Fix as IsExpiresFilled is always true
        public function HasExpires()
        {
            return $this->Expires != SoftEtherConverter::LocalEpoch;
        }
    }
}