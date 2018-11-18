<?php

namespace SoftEtherApi\Model
{
    class AuthType
    {
        const Anonymous = 0; // Anonymous authentication
        const Password = 1; // Password authentication
        const Usercert = 2; // User certificate authentication
        const Rootcert = 3; // Root certificate which is issued by trusted Certificate Authority
        const Radius = 4; // Radius authentication
        const Nt = 5; // Windows NT authentication
        const OpenvpnCert = 98; // TLS client certificate authentication
        const Ticket = 99; // Ticket authentication
    }
}