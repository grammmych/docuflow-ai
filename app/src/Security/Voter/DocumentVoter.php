<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;
use App\Entity\Document;

final class DocumentVoter extends Voter
{
    public const VIEW = 'DOCUMENT_VIEW';    

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::VIEW
            && $subject instanceof Document;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return $subject->getOwner()?->getId() === $user->getId();
    }
}
