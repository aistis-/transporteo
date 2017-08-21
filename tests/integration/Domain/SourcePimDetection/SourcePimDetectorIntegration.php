<?php

declare(strict_types=1);

namespace integration\Akeneo\PimMigration\Domain\SourcePimDetection;

use Akeneo\PimMigration\Domain\PimConfiguration\ComposerJson;
use Akeneo\PimMigration\Domain\PimConfiguration\ParametersYml;
use Akeneo\PimMigration\Domain\PimConfiguration\PimConfiguration;
use Akeneo\PimMigration\Domain\PimConfiguration\PimParameters;
use Akeneo\PimMigration\Domain\SourcePimDetection\SourcePim;
use PHPUnit\Framework\TestCase;
use resources\Akeneo\PimMigration\ResourcesFileLocator;

/**
 * Source Pim Detector Integration.
 *
 * @author    Anael Chardan <anael.chardan@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 */
class SourcePimDetectorIntegration extends TestCase
{
    public function testSimpleCommunityStandardEdition()
    {
        $sourcePim = SourcePim::fromSourcePimConfiguration($this->getPimConfiguration('simple-pim-community-standard'));

        $this->assertEquals($sourcePim->getDatabaseName(), 'akeneo_pim_database_name');
        $this->assertEquals($sourcePim->getMysqlHost(), 'localhost');
        $this->assertEquals($sourcePim->getMysqlPort(), 3306);
        $this->assertEquals($sourcePim->getDatabaseUser(), 'akeneo_pim_user');
        $this->assertEquals($sourcePim->getDatabasePassword(), 'akeneo_pim_password');
        $this->assertEquals($sourcePim->isEnterpriseEdition(), false);
        $this->assertEquals($sourcePim->hasIvb(), false);
        $this->assertEquals($sourcePim->getMongoDbInformation(), null);
        $this->assertEquals($sourcePim->getMongoDatabase(), null);
    }

    public function testEnterpriseStandardEditionMongoIvb()
    {
        $sourcePim = SourcePim::fromSourcePimConfiguration($this->getPimConfiguration('ivb-mongo-pim-entreprise-standard'));

        $this->assertEquals($sourcePim->getDatabaseName(), 'akeneo_pim_database_name');
        $this->assertEquals($sourcePim->getMysqlHost(), 'localhost');
        $this->assertEquals($sourcePim->getMysqlPort(), 3306);
        $this->assertEquals($sourcePim->getDatabaseUser(), 'akeneo_pim_user');
        $this->assertEquals($sourcePim->getDatabasePassword(), 'akeneo_pim_password');
        $this->assertEquals($sourcePim->isEnterpriseEdition(), true);
        $this->assertEquals($sourcePim->getEnterpriseRepository(), 'ssh://git@distribution.akeneo.com:443/pim-enterprise-dev-nanou-migration.git');
        $this->assertEquals($sourcePim->hasIvb(), true);
        $this->assertEquals($sourcePim->getMongoDbInformation(), 'mongodb://localhost:27017');
        $this->assertEquals($sourcePim->getMongoDatabase(), 'your_mongo_database');
    }

    private function getPimConfiguration(string $pimConfigurationName): ?PimConfiguration
    {
        if ('simple-pim-community-standard' === $pimConfigurationName) {
            $stepTwoFolder = sprintf(
                '%s%s%s%s',
                ResourcesFileLocator::getStepFolder('step_two_source_pim_detection'),
                DIRECTORY_SEPARATOR,
                'community_standard',
                DIRECTORY_SEPARATOR
            );
            $standardComposerJson = $stepTwoFolder . 'composer.json';
            $parametersYaml = $stepTwoFolder . 'parameters.yml';
            $pimParameters = $stepTwoFolder . 'pim_parameters.yml';

            return new PimConfiguration(
                new ComposerJson($standardComposerJson),
                new ParametersYml($parametersYaml),
                new PimParameters($pimParameters),
                'plop'
            );
        }

        if ('ivb-mongo-pim-entreprise-standard' === $pimConfigurationName) {
            $stepTwoFolder = sprintf(
                '%s%s%s%s',
                ResourcesFileLocator::getStepFolder('step_two_source_pim_detection'),
                DIRECTORY_SEPARATOR,
                'enterprise_mongo_ivb_standard',
                DIRECTORY_SEPARATOR
            );
            $standardComposerJson = $stepTwoFolder . 'composer.json';
            $parametersYaml = $stepTwoFolder . 'parameters.yml';
            $pimParameters = $stepTwoFolder . 'pim_parameters.yml';

            return new PimConfiguration(
                new ComposerJson($standardComposerJson),
                new ParametersYml($parametersYaml),
                new PimParameters($pimParameters),
                'plop'
            );
        }

        return null;
    }
}