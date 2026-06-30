<?php

namespace App\Enum;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case INTERVIEW = 'interview';
    case OFFER = 'offer';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}