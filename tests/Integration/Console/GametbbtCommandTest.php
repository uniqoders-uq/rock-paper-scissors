<?php

namespace Uniqoders\Tests\Integration\Console;

use Symfony\Component\Console\Tester\CommandTester;
use Uniqoders\Game\Console\GametbbtCommand;
use Uniqoders\Tests\Integration\IntegrationTestCase;

class GametbbtCommandTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->application->add(new GametbbtCommand());
    }

    public function test_game_command(): void
    {
        /**
         *******************
         * TODO Make tests *
         *******************
         */
        $command = $this->application->find('gametbbt');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();

        $this->assertIsString($output);
    }
}
