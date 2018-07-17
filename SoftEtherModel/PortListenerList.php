<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');
    use SoftEtherApi\Model\BaseSoftEtherModel;

    class PortListenerList extends BaseSoftEtherModel
    {
        public $Enables;
        public $Errors;
        public $Ports;
    }
}