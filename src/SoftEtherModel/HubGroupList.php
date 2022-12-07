<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubGroupList extends BaseSoftEtherModel {
		public $DenyAccess;
		public $HubName;
		public $Name;
		public $Note;
		public $NumUsers;
		public $Realname;
	}
