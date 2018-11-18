<?php

namespace SoftEtherApi\SoftEtherModel
{
    use SoftEtherApi\Model;

    class L2tpSettings extends Model\BaseSoftEtherModel
    {
        public $L2TP_Raw; //L2TP without encryption
        public $L2TP_IPsec; //L2TP with IPSec
        public $EtherIP_IPsec;
        public $L2TP_DefaultHub;
        public $IPsec_Secret;
    }
}

