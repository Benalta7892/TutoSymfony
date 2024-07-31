<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RecipeVoter extends Voter
{
  public const EDIT = 'RECIPE_EDIT';
  public const VIEW = 'RECIPE_VIEW';

  protected function supports(string $attribute, mixed $subject): bool
  {
    return in_array($attribute, [self::EDIT, self::VIEW])
      && $subject instanceof \App\Entity\Recipe;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    $user = $token->getUser();

    // if the user is anonymous, do not grant access
    if (!$user instanceof User) {
      return false;
    }

    if (!$subject instanceof Recipe) {
      return false;
    }

    // ... (check conditions and return true to grant permission) ...
    switch ($attribute) {
      case self::EDIT:
        return $subject->getUser()->getId() === $user->getId();
        break;

      case self::VIEW:
        // logic to determine if the user can VIEW
        // return true or false
        break;
    }

    return false;
  }
}
