<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class ServerStatus extends BaseSoftEtherModel {
		public $AssignedBridgeLicenses;
		public $AssignedBridgeLicensesTotal;
		public $AssignedClientLicenses;
		public $AssignedClientLicensesTotal;
		public $CurrentTick;
		public $CurrentTime;
		public $FreeMemory;
		public $FreePhys;
		public $NumGroups;
		public $NumHubDynamic;
		public $NumHubStandalone;
		public $NumHubStatic;
		public $NumHubTotal;
		public $NumIpTables;
		public $NumMacTables;
		public $NumSessionsLocal;
		public $NumSessionsRemote;
		public $NumSessionsTotal;
		public $NumTcpConnections;
		public $NumTcpConnectionsLocal;
		public $NumTcpConnectionsRemote;
		public $NumUsers;
		public $RecvBroadcastBytes;
		public $RecvBroadcastCount;
		public $RecvUnicastBytes;
		public $RecvUnicastCount;
		public $SendBroadcastBytes;
		public $SendBroadcastCount;
		public $SendUnicastBytes;
		public $SendUnicastCount;
		public $ServerType;
		public $StartTime;
		public $TotalMemory;
		public $TotalPhys;
		public $UsedMemory;
		public $UsedPhys;
	}
