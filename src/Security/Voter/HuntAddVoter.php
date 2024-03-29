<?php

namespace App\Security\Voter;

use App\Entity\Hunt;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HuntAddVoter extends Voter
{
    public const ADD = 'add';
    public const CLOSE = 'close';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ADD, self::CLOSE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            self::ADD => $this->checkIfPoliceman($user),
            self::CLOSE => $this->checkIfPoliceman($user),
            default => throw new \LogicException('This code should not be reached!')
        };

    }

    private function checkIfPoliceman(User $user): bool
    {
        return in_array('ROLE_POLICEMAN', $user->getRoles());
    }
}
