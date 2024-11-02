<?php

declare(strict_types=1);

namespace Bannerstop\TunneledMysql;

use Bannerstop\TunneledMysql\MySQL\MySQLCredentials;
use Bannerstop\TunneledMysql\MySQL\MySqlTunnel;
use Bannerstop\TunneledMysql\SSH\SSHCommandBuilder;
use Bannerstop\TunneledMysql\SSH\SSHCredentials;

class TunnelBuilder
{
    private SSHCredentials $sshCredentials;
    private MySQLCredentials $mysqlCredentials;
    private int $localPort;

    public function __construct(
        SSHCredentials $sshCredentials,
        MySQLCredentials $mysqlCredentials,
        int $localPort
    )
    {
        $this->sshCredentials = $sshCredentials;
        $this->mysqlCredentials = $mysqlCredentials;
        $this->localPort = $localPort;
    }

    public function build(): MySqlTunnel
    {
        $sshCommand = (new SSHCommandBuilder($this->sshCredentials, $this->mysqlCredentials, $this->localPort))->build();

        $descriptorSpec = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w']  // stderr
        ];

        return new MySqlTunnel($this->mysqlCredentials, $this->localPort, $sshCommand, $descriptorSpec);
    }
}
