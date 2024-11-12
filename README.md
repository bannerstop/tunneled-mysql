# MySQL via SSH Tunnel

Using a MySQL server via an SSH tunnel.

## Notice

SSH Tunnel does not currently support requesting the SSH password for the private key provided, so make sure your SSH key does not require a password.

If you are not sure whether your SSH key pair requires a passphrase, you can create a new SSH key pair

```bash
# generate key pair without passphrase
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa_no_pass -N ""

# copy public key to remote server
ssh-copy-id -i ~/.ssh/id_rsa_no_pass.pub user@host
```

Make sure that your PHP application has the authorization to access the private SSH key file

## Installation

Use Composer to install:

```bash
composer require bannerstop/tunneled-mysql
```

## Usage

```php
$tunnelBuilder = new \Bannerstop\TunneledMysql\TunnelBuilder(
    new \Bannerstop\TunneledMysql\SSH\SSHCredentials(
        'ssh.example.host',
        'user',
        'path/to/private_key'
    ),
    new \Bannerstop\TunneledMysql\MySQL\MySQLCredentials(
        'username',
        'pass',
        'dbname'
    ),
    (new \Bannerstop\TunneledMysql\Lib\GetAvailablePort())->execute()
);
$mysql = $tunnelBuilder->build()->run();

$query = $mysql->query('SELECT * FROM example LIMIT 1');
print_r($query->fetch());
```
