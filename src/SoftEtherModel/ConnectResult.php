<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class ConnectResult extends BaseSoftEtherModel {
		public $build;
		public $hello;
		public $pencore;
		public $random;
		public $version;
	}
