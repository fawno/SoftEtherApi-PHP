<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class Hub extends BaseSoftEtherModel {
			public $HashedPassword;
			public $HubName;
			public $HubType;
			public $MaxSession;
			public $NoEnum;
			public $Online;
			public $SecurePassword;
	}
