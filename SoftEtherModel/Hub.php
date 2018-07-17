<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class Hub extends BaseSoftEtherModel
    {
        public $HashedPassword;
        public $HubName;
        public $HubType;
        public $MaxSession;
        public $NoEnum;
        public $Online;
        public $SecurePassword;
    }
}