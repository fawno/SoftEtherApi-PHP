<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class VirtualHostOptions extends BaseSoftEtherModel {
		public $ApplyDhcpPushRoutes;
		public $DhcpDnsServerAddress;
		public $DhcpDnsServerAddress2;
		public $DhcpDnsServerAddress2_ipv6_array;
		public $DhcpDnsServerAddress2_ipv6_bool;
		public $DhcpDnsServerAddress2_ipv6_scope_id;
		public $DhcpDnsServerAddress_ipv6_array;
		public $DhcpDnsServerAddress_ipv6_bool;
		public $DhcpDnsServerAddress_ipv6_scope_id;
		public $DhcpDomainName;
		public $DhcpExpireTimeSpan;
		public $DhcpGatewayAddress;
		public $DhcpGatewayAddress_ipv6_array;
		public $DhcpGatewayAddress_ipv6_bool;
		public $DhcpGatewayAddress_ipv6_scope_id;
		public $DhcpLeaseIPEnd;
		public $DhcpLeaseIPEnd_ipv6_array;
		public $DhcpLeaseIPEnd_ipv6_bool;
		public $DhcpLeaseIPEnd_ipv6_scope_id;
		public $DhcpLeaseIPStart;
		public $DhcpLeaseIPStart_ipv6_array;
		public $DhcpLeaseIPStart_ipv6_bool;
		public $DhcpLeaseIPStart_ipv6_scope_id;
		public $DhcpSubnetMask;
		public $DhcpSubnetMask_ipv6_array;
		public $DhcpSubnetMask_ipv6_bool;
		public $DhcpSubnetMask_ipv6_scope_id;
		public $Ip;
		public $Ip_ipv6_array;
		public $Ip_ipv6_bool;
		public $Ip_ipv6_scope_id;
		public $MacAddress;
		public $Mask;
		public $Mask_ipv6_array;
		public $Mask_ipv6_bool;
		public $Mask_ipv6_scope_id;
		public $Mtu;
		public $NatTcpTimeout;
		public $NatUdpTimeout;
		public $RpcHubName;
		public $SaveLog;
		public $UseDhcp;
		public $UseNat;
		public $DhcpPushRoutes;
	}
