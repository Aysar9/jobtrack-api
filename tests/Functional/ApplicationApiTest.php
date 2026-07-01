<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationApiTest extends WebTestCase
{
    public function testCreateApplicationWithValidData(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'company' => 'Test GmbH',
                'position' => 'Developer',
                'appliedAt' => '2026-07-01',
            ])
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateApplicationWithEmptyCompanyFails(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'company' => '',
                'position' => 'Developer',
                'appliedAt' => '2026-07-01',
            ])
        );

        $this->assertResponseStatusCodeSame(422);
    }

    public function testGetNonExistentApplicationReturns404(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/applications/99999');

        $this->assertResponseStatusCodeSame(404);
    }
}