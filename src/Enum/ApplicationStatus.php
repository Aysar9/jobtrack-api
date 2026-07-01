<?php

namespace App\Enum;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case INTERVIEW = 'interview';
    case OFFER = 'offer';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    
    /**
     * Gibt zurück, in welche Status von diesem aus gewechselt werden darf.
     *
     * @return ApplicationStatus[]
     */
    
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::APPLIED   => [self::INTERVIEW, self::REJECTED],
            self::INTERVIEW => [self::OFFER, self::REJECTED],
            self::OFFER     => [self::ACCEPTED, self::REJECTED],
            self::ACCEPTED  => [],
            self::REJECTED  => [],
        };
    }

    /**
     * Prüft, ob der Wechsel zu einem Zielstatus erlaubt ist.
     */
    public function canTransitionTo(ApplicationStatus $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}