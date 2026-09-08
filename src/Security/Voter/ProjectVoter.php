<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProjectVoter extends Voter
{
    public const string EDIT = 'PROJECT_EDIT';
    public const string DELETE = 'PROJECT_DELETE';
    public const string VIEW = 'PROJECT_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {

        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW], true)
            && $subject instanceof Project;
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if(!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in.');
            return false;
        }

        if(in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }


        $project = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::DELETE => $this->canDelete($project, $user, $vote),
            self::EDIT => $this->canEdit($project, $user, $vote),
            self::VIEW => $this->canView($project, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canDelete(Project $project, UserInterface $user, ?Vote $vote): bool
    {// if they can edit, they can delete
        if($project->getOwner() === $user) {
            return true;
        }

        $vote?->addReason('You cannot delete this project.');

        // the Post object could have, for example, a method `isPrivate()`
        return false;
    }

    private function canEdit(Project $project, UserInterface $user, ?Vote $vote): bool
    {
        // this assumes that the Post object has a `getAuthor()` method
        if ($project->getOwner() === $user) {
            return true;
        }

        $vote?->addReason(sprintf(
            'User %s is not the owner of project #%d.',
            $user->getUserIdentifier(), $project->getId()
        ));

        return false;
    }

    private function canView(Project $project, UserInterface $user, ?Vote $vote): bool
    {
        // this assumes that the Post object has a `getAuthor()` method
        if ($project->getOwner() === $user) {
            return true;
        }

        $vote?->addReason(sprintf(
            'User %s is not the owner of project #%d.',
            $user->getUserIdentifier(), $project->getId()
        ));

        return false;
    }


}
