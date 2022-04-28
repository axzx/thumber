<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateThumbCommandTest extends KernelTestCase
{
    public function testWrongFilterPayload(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:generate-thumb');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filepath' => 'photos/image1.jpg',
            'filter' => 'wrong_filter',
        ]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            '[ERROR] filter: The value you selected is not a valid choice.',
            $commandTester->getDisplay()
        );
    }

    public function testCorrectPayload(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:generate-thumb');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filepath' => 'photos/image1.jpg',
            'filter' => 'min',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
