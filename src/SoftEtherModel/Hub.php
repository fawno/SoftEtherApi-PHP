<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class Hub extends Model\BaseSoftEtherModel
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