<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class HubRadius extends Model\BaseSoftEtherModel
    {
        public $HubName;
        public $RadiusPort;
        public $RadiusRetryInterval;
        public $RadiusSecret;
        public $RadiusServerName;
    }
}