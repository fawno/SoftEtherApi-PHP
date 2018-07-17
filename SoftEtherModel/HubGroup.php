<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class HubGroup extends BaseSoftEtherModel
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