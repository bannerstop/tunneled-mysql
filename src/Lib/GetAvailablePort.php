<?php

declare(strict_types=1);

namespace Bannerstop\TunneledMysql\Lib;

use RuntimeException;

class GetAvailablePort
{
    public function execute(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        if ($socket === false) {
            throw new RuntimeException('Failed to find an available port.');
        }
        $address = stream_socket_get_name($socket, false);
        fclose($socket);
        $parts = parse_url('tcp://' . $address);

        return (int) $parts['port'];
    }
}
