<?php

namespace App\Tests\Command;

use App\CommandBus\UserGenerateThumbCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class UserGenerateThumbCommandTest extends KernelTestCase
{
    use InteractsWithMessenger;

    public function testCorrectPayload(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:user:generate-thumb');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertStringContainsString(
            'Processed Records: 9',
            $commandTester->getDisplay()
        );

        $this->messenger()->queue()->assertCount(9);
        $this->messenger()->queue()->assertContains(UserGenerateThumbCommand::class);
        $this->messenger()->process();
        $this->messenger()->queue()->assertCount(0);
    }
}
