<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Model;
	class AuthType {
		public const Anonymous   =  0; // Anonymous authentication
		public const Password    =  1; // Password authentication
		public const Usercert    =  2; // User certificate authentication
		public const Rootcert    =  3; // Root certificate which is issued by trusted Certificate Authority
		public const Radius      =  4; // Radius authentication
		public const Nt          =  5; // Windows NT authentication
		public const OpenvpnCert = 98; // TLS client certificate authentication
		public const Ticket      = 99; // Ticket authentication

		public const AuthTypes = [
				self::Anonymous   => 'Anonymous authentication',
				self::Password    => 'Password authentication',
				self::Usercert    => 'User certificate authentication',
				self::Rootcert    => 'Root certificate which is issued by trusted Certificate Authority',
				self::Radius      => 'Radius authentication',
				self::Nt          => 'Windows NT authentication',
				self::OpenvpnCert => 'TLS client certificate authentication',
				self::Ticket      => 'Ticket authentication',
		];
	}
