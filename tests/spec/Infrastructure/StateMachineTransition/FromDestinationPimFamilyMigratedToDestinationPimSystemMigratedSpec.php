<?php

declare(strict_types=1);

namespace spec\Akeneo\PimMigration\Infrastructure\StateMachineTransition;

use Akeneo\PimMigration\Domain\DestinationPimInstallation\DestinationPim;
use Akeneo\PimMigration\Domain\SourcePimDetection\SourcePim;
use Akeneo\PimMigration\Domain\SystemMigration\SystemMigrator;
use Akeneo\PimMigration\Infrastructure\MigrationToolStateMachine;
use Akeneo\PimMigration\Infrastructure\StateMachineTransition\FromDestinationPimFamilyMigratedToDestinationPimSystemMigrated;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Workflow\Event\Event;

/**
 * Spec for FromDestinationPimFamilyMigratedToDestinationPimSystemMigrated.
 *
 * @author    Anael Chardan <anael.chardan@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 */
class FromDestinationPimFamilyMigratedToDestinationPimSystemMigratedSpec extends ObjectBehavior
{
    public function let(
        Translator $translator,
        SystemMigrator $migrator
    ) {
        $this->beConstructedWith($translator, $migrator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FromDestinationPimFamilyMigratedToDestinationPimSystemMigrated::class);
    }

    public function it_migrates_system(
        Event $event,
        MigrationToolStateMachine $stateMachine,
        SourcePim $sourcePim,
        DestinationPim $destinationPim,
        $migrator
    ) {
        $event->getSubject()->willReturn($stateMachine);
        $stateMachine->getSourcePim()->willReturn($sourcePim);
        $stateMachine->getDestinationPim()->willReturn($destinationPim);

        $migrator->migrate($sourcePim, $destinationPim)->shouldBeCalled();

        $this->onDestinationPimSystemMigration($event);
    }
}
