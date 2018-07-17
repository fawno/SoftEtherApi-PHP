<?php

namespace SoftEtherApi\SoftEtherModel
{
    require_once('Model/BaseSoftEtherModel.php');

    use SoftEtherApi\Model\BaseSoftEtherModel;

    class ConnectResult extends BaseSoftEtherModel
    {
        public $build;
        public $hello;
        public $pencore;
        public $random;
        public $version;
    }
}