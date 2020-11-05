<?php
declare(strict_types=1);

class GenerateContractService
{
    private $signatureRepository;

    public function __construct(SignatureRepositoryInterface $signatureRepository)
    {
        $this->signatureRepository = $signatureRepository;
    }


    /**
     * @param string $contractWeirdFormat
     *
     * @throws InvalidContractFormatException
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     *
     * @return Contract
     */
    public function execute(string $contractWeirdFormat): Contract
    {
        $this->checkContractFormat($contractWeirdFormat);

        $partiesLists = explode(Contract::SEPARATOR_VALUE, $contractWeirdFormat);

        if (count($partiesLists) != Contract::CONTRACT_NUM_PARTIES ) {
            throw new InvalidContractFormatException("Contract should be in KN vs NNV format");
        }

        $plaintiffSignatures = $this->generateSignatures($partiesLists[0]);
        $defendantSignatures = $this->generateSignatures($partiesLists[1]);

        return new Contract(
            $plaintiffSignatures,
            $defendantSignatures
        );
    }


    /**
     * @param string $signaturesList
     *
     * @return array
     * @throws InvalidSignatureRoleValueException
     * @throws SignatureNotFoundException
     */
    private function generateSignatures(string $signaturesList): array
    {
        $partySignatures = [];

        $roles = str_split($signaturesList);

        foreach ($roles as $role) {
            $partySignatures[] = $this->signatureRepository->findByRole(new SignatureRole($role));
        }

        return $partySignatures;
    }

    /**
     * @param string $signaturesList
     *
     * @throws InvalidContractFormatException
     */
    private function checkContractFormat(string $signaturesList)
    {
        if ( ! strpos(Contract::SEPARATOR_VALUE, $signaturesList)) {
            throw new InvalidContractFormatException("Contract should be in KN vs NNV format");
        }
    }



}
