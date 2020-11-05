<?php
declare(strict_types=1);

namespace Signaturit\Cesc\Infrastructure\Endpoint;


use Signaturit\Cesc\Application\performTrialService;

// that would be a controller for example where to make http requests for example.
class PerformTrial
{
    private $performTrialService;

    public function __construct(performTrialService $performTrialService)
    {
        $this->performTrialService = $performTrialService;
    }

    /**
     * @param string $contractString
     *
     * @return string
     */
    public function performTrial(string $contractString): string
    {
        return $this->performTrialService->execute($contractString);
    }

}
