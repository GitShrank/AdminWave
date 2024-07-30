<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

use App\Tenant;

if ($argc < 3) {
    echo "Usage: php run_tenant_command.php <tenant_id> <command>\n";
    exit(1);
}

$tenantId = $argv[1];
$command = $argv[2];

$output = Tenant::runCommand($tenantId, $command);
echo $output;
