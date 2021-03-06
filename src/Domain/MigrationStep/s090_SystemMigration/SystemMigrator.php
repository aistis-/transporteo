<?php

declare(strict_types=1);

namespace Akeneo\PimMigration\Domain\MigrationStep\s090_SystemMigration;

use Akeneo\PimMigration\Domain\Command\ChainedConsole;
use Akeneo\PimMigration\Domain\Command\MySqlExecuteCommand;
use Akeneo\PimMigration\Domain\DataMigration\DataMigrationException;
use Akeneo\PimMigration\Domain\DataMigration\DataMigrator;
use Akeneo\PimMigration\Domain\Pim\DestinationPim;
use Akeneo\PimMigration\Domain\Pim\SourcePim;

/**
 * System migration `user`, `group`, `role`.
 *
 * @author    Anael Chardan <anael.chardan@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 */
class SystemMigrator
{
    private $systemMigrators = [];

    /** @var ChainedConsole */
    private $console;

    public function __construct(ChainedConsole $console)
    {
        $this->console = $console;
    }

    /**
     * @throws SystemMigrationException
     */
    public function migrate(SourcePim $sourcePim, DestinationPim $destinationPim): void
    {
        try {
            foreach ($this->systemMigrators as $systemMigrator) {
                $systemMigrator->migrate($sourcePim, $destinationPim);
            }

            $queries = [];
            $queries[] =
                'ALTER TABLE pim_api_access_token
                ADD COLUMN client int(11) DEFAULT NULL AFTER id,
                ADD CONSTRAINT FK_BD5E4023C7440455 FOREIGN KEY (client) REFERENCES pim_api_client (id) ON DELETE CASCADE;';

            $queries[] = 'CREATE INDEX IDX_BD5E4023C7440455 ON pim_api_access_token (client);';

            $queries[] = 'UPDATE pim_api_client SET label = id WHERE label IS NULL;';

            foreach ($queries as $query) {
                $this->console->execute(new MySqlExecuteCommand($query), $destinationPim);
            }
        } catch (DataMigrationException $exception) {
            throw new SystemMigrationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function addSystemMigrator(DataMigrator $systemMigrator): void
    {
        $this->systemMigrators[] = $systemMigrator;
    }
}
