<?php


require dirname(__DIR__).'/challenge/vendor/autoload.php';

use Signaturit\Cesc\Application\performTrialService;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Infrastructure\Endpoint\PerformTrial;

// Configuration of symfony would beautifully load and do everything that I am doing here via config files.
$performTrialEndpoint = new PerformTrial(
    new performTrialService(
        new GenerateContractService(
            new SignatureRepository()
        )
    )
);

if (!isset($argv[1])) {
    echo "Usage is php PerformTrialEndpoint KNVSNNV";
    die();
}

try {
    $return = $performTrialEndpoint->performTrial($argv[1]);
    echo $return."\n";
} catch (Exception $e) {
    echo $e->getMessage()."\n";
}





