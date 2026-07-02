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

    public function testForbiddenStatusTransitionReturns422(): void
    {
        $client = static::createClient();

        // Erst eine Bewerbung anlegen (Status ist per Default "applied")
        $client->request(
            'POST',
            '/api/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'company' => 'Transition Test GmbH',
                'position' => 'Developer',
                'appliedAt' => '2026-07-01',
            ])
        );

        $created = json_decode($client->getResponse()->getContent(), true);
        $id = $created['id'];

        // Verbotener Übergang: applied → accepted (nicht erlaubt)
        $client->request(
            'PATCH',
            '/api/applications/' . $id . '/status',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['status' => 'accepted'])
        );

        $this->assertResponseStatusCodeSame(422);
    }
}