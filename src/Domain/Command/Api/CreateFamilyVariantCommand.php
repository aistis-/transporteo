<?php

declare(strict_types=1);

namespace Akeneo\PimMigration\Domain\Command\Api;

use Akeneo\PimMigration\Domain\Command\ApiCommand;

/**
 * @author    Philippe Mossière <philippe.mossiere@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 */
class CreateFamilyVariantCommand implements ApiCommand
{
    /** @var string */
    private $code;

    /** @var string */
    private $familyCode;

    /** @var array */
    private $data;

    public function __construct(string $familyCode, string $code, array $data)
    {
        $this->code = $code;
        $this->familyCode = $familyCode;
        $this->data = $data;
    }

    public function getCommand(): string
    {
        return sprintf('Create variant %s for the family %s', $this->code, $this->familyCode);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getFamilyCode(): string
    {
        return $this->familyCode;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
