<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubGroup extends Model\BaseSoftEtherModel
    {
        public $HubName;
        public $Name;
        public $Note;
        public $Realname;
        public $RecvBroadcastBytes;
        public $RecvBroadcastCount;
        public $RecvUnicastBytes;
        public $RecvUnicastCount;
        public $SendBroadcastBytes;
        public $SendBroadcastCount;
        public $SendUnicastBytes;
        public $SendUnicastCount;
    }
}