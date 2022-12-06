<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class L2tpSettings extends BaseSoftEtherModel {
		public $L2TP_Raw; //L2TP without encryption
		public $L2TP_IPsec; //L2TP with IPSec
		public $EtherIP_IPsec;
		public $L2TP_DefaultHub;
		public $IPsec_Secret;
	}
