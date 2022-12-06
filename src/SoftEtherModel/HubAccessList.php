<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubAccessList extends BaseSoftEtherModel {
		public $HubName;
		public $Id;
		public $UniqueId;

		public $Note = '';
		public $Active;
		public $Discard;
		public $Priority;
		public $SrcUsername = '';
		public $DestUsername = '';

		public $DestIpAddress = 0;
		public $DestSubnetMask = 0;
		public $SrcIpAddress = 0;
		public $SrcSubnetMask = 0;
		public $Protocol = 0;
		public $SrcPortEnd = 0;
		public $SrcPortStart = 0;
		public $DestPortEnd = 0;
		public $DestPortStart = 0;

		public $RedirectUrl = '';

		public $Jitter;
		public $Loss;
		public $Delay;

		public $CheckTcpState = false;
		public $Established;

		public $CheckSrcMac = false;
		public $SrcMacAddress;
		public $SrcMacMask;
		public $CheckDstMac = false;
		public $DstMacAddress;
		public $DstMacMask;

		public $IsIPv6 = false;
		public $DestIpAddress6;
		public $DestIpAddress_ipv6_array;
		public $DestIpAddress_ipv6_bool;
		public $DestIpAddress_ipv6_scope_id;
		public $DestSubnetMask6;
		public $DestSubnetMask_ipv6_array;
		public $DestSubnetMask_ipv6_bool;
		public $DestSubnetMask_ipv6_scope_id;
		public $SrcIpAddress6;
		public $SrcIpAddress_ipv6_array;
		public $SrcIpAddress_ipv6_bool;
		public $SrcIpAddress_ipv6_scope_id;
		public $SrcSubnetMask6;
		public $SrcSubnetMask_ipv6_array;
		public $SrcSubnetMask_ipv6_bool;
		public $SrcSubnetMask_ipv6_scope_id;

		public function __construct () {
			parent::__construct();
			$this->SrcMacAddress = array_merge(array_fill(0, 6, 0));
			$this->SrcMacMask = array_merge(array_fill(0, 6, 0));
			$this->DstMacAddress = array_merge(array_fill(0, 6, 0));
			$this->DstMacMask = array_merge(array_fill(0, 6, 0));

			$this->DestIpAddress6 = array_merge(array_fill(0, 16, 0));
			$this->DestIpAddress_ipv6_array = array_merge(array_fill(0, 16, 0));
			$this->DestSubnetMask6 = array_merge(array_fill(0, 16, 0));
			$this->DestSubnetMask_ipv6_array = array_merge(array_fill(0, 16, 0));
			$this->SrcIpAddress6 = array_merge(array_fill(0, 16, 0));
			$this->SrcIpAddress_ipv6_array = array_merge(array_fill(0, 16, 0));
			$this->SrcSubnetMask6 = array_merge(array_fill(0, 16, 0));
			$this->SrcSubnetMask_ipv6_array = array_merge(array_fill(0, 16, 0));
		}
	}
