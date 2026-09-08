<?php

namespace App\Security\Voter;

use App\Entity\Project;
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
        // Handle only project-level permissions supported by this voter.
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW], true)
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // Anonymous users cannot access project resources.
        if(!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in.');
            return false;
        }

        // Administrators can manage any project regardless of ownership.
        if(in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Standard users are authorized according to project ownership.
        $project = $subject;

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

        return false;
    }

    private function canEdit(Project $project, UserInterface $user, ?Vote $vote): bool
    {
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
