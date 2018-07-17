<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class HubLog extends BaseSoftEtherModel
    {
        public $HubName;
        public $PacketLogConfig;
        public $PacketLogSwitchType;
        public $SavePacketLog;
        public $SaveSecurityLog;
        public $SecurityLogSwitchType;
    }
}