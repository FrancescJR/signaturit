<?php


use Signaturit\Cesc\Application\CalculateMinimumSignatureService;
use Signaturit\Cesc\Domain\Contract\Service\GenerateContractService;
use Signaturit\Cesc\Infrastructure\Endpoint\CalculateMinimumSignature;
use Signaturit\Cesc\Infrastructure\Persistence\SignatureRepository;

require dirname(__DIR__) . '/challenge/vendor/autoload.php';


// Configuration of symfony would beautifully load and do everything that I am doing here via config files.
$signatureRepository        = new SignatureRepository();
$calculateSignatureEndpoint = new CalculateMinimumSignature(
    new CalculateMinimumSignatureService(
        new GenerateContractService(
            $signatureRepository
        ),
        $signatureRepository
    )
);

if ( ! isset($argv[1])) {
    echo "Usage is php CalculateSignatureEndpoint K#VSNNVV";
    die();
}

try {
    $return = $calculateSignatureEndpoint->calculateMinimumSignature($argv[1]);
    echo $return . "\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
