<?php

namespace App\Tests\Unit\Enum;

use App\Enum\ApplicationStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ApplicationStatusTest extends TestCase
{
    #[DataProvider('transitionProvider')]
    public function testTransitions(
        ApplicationStatus $from,
        ApplicationStatus $to,
        bool $expected
    ): void {
        $this->assertSame($expected, $from->canTransitionTo($to));
    }

    public static function transitionProvider(): array
    {
        return [
            // Beschreibung => [Ausgangsstatus, Zielstatus, erlaubt?]
            'applied → interview (erlaubt)'   => [ApplicationStatus::APPLIED, ApplicationStatus::INTERVIEW, true],
            'applied → rejected (erlaubt)'    => [ApplicationStatus::APPLIED, ApplicationStatus::REJECTED, true],
            'applied → offer (verboten)'      => [ApplicationStatus::APPLIED, ApplicationStatus::OFFER, false],
            'applied → accepted (verboten)'   => [ApplicationStatus::APPLIED, ApplicationStatus::ACCEPTED, false],
            'interview → offer (erlaubt)'     => [ApplicationStatus::INTERVIEW, ApplicationStatus::OFFER, true],
            'interview → applied (verboten)'  => [ApplicationStatus::INTERVIEW, ApplicationStatus::APPLIED, false],
            'offer → accepted (erlaubt)'      => [ApplicationStatus::OFFER, ApplicationStatus::ACCEPTED, true],
            'offer → rejected (erlaubt)'      => [ApplicationStatus::OFFER, ApplicationStatus::REJECTED, true],
            'accepted → offer (verboten)'     => [ApplicationStatus::ACCEPTED, ApplicationStatus::OFFER, false],
            'rejected → offer (verboten)'     => [ApplicationStatus::REJECTED, ApplicationStatus::OFFER, false],
            'rejected → interview (verboten)' => [ApplicationStatus::REJECTED, ApplicationStatus::INTERVIEW, false],
        ];
    }
}