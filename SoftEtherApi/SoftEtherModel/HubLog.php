<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubLog extends Model\BaseSoftEtherModel
    {
        public $HubName;
        public $PacketLogConfig;
        public $PacketLogSwitchType;
        public $SavePacketLog;
        public $SaveSecurityLog;
        public $SecurityLogSwitchType;
    }
}