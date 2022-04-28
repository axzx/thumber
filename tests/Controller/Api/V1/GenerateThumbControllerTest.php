<?php

namespace App\Tests\Controller\Api\V1;

use App\CommandBus\GenerateThumbCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class GenerateThumbControllerTest extends WebTestCase
{
    use InteractsWithMessenger;

    public function testMethodGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/generate/thumb');

        $this->assertResponseStatusCodeSame(405);
    }

    public function testMethodPostWithEmptyPayload(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/generate/thumb');

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode((string) $client->getResponse()->getContent(), true);
        $this->assertEquals([
            'filepath' => 'This value should not be blank.',
            'filter' => 'This value should not be blank.',
        ], $data);
    }

    public function testMethodPostWithNotExistsFilter(): void
    {
        $json = <<<JSON
            {
                "filepath": "photos/image1.jpg", 
                "filter": "max_notexists"
            }
        JSON;
        $client = static::createClient();
        $client->request('POST', '/api/v1/generate/thumb', [
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        ], [], [], $json);
        $this->assertResponseStatusCodeSame(400);
        $data = json_decode((string) $client->getResponse()->getContent(), true);
        $this->assertEquals([
            'filter' => 'The value you selected is not a valid choice.',
        ], $data);
    }

    public function testMethodPostWithCorrectPayload(): void
    {
        $client = static::createClient();

        $this->messenger()->queue()->assertEmpty();

        $json = <<<JSON
            {
                "filepath": "photos/image1.jpg", 
                "filter": "min"
            }
        JSON;
        $client->request('POST', '/api/v1/generate/thumb', [
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        ], [], [], $json);
        $this->assertResponseStatusCodeSame(200);

        $this->messenger()->queue()->assertCount(1);
        $this->messenger()->queue()->assertContains(GenerateThumbCommand::class);
        $this->messenger()->process(1);
        $this->messenger()->queue()->assertCount(0);
    }

    public function testMethodPostWithFileNotExists(): void
    {
        $client = static::createClient();

        $this->messenger()->queue()->assertEmpty();

        $json = <<<JSON
            {
                "filepath": "photos/not-exists.jpg", 
                "filter": "min"
            }
        JSON;
        $client->request('POST', '/api/v1/generate/thumb', [
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        ], [], [], $json);
        $this->assertResponseStatusCodeSame(200);

        $this->messenger()->queue()->assertCount(1);
        $this->messenger()->queue()->assertContains(GenerateThumbCommand::class);
        $this->messenger()->process(1);
        $this->messenger()->queue()->assertCount(0);
    }
}
