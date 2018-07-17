<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class HubRadius extends BaseSoftEtherModel
    {
        public $HubName;
        public $RadiusPort;
        public $RadiusRetryInterval;
        public $RadiusSecret;
        public $RadiusServerName;
    }
}