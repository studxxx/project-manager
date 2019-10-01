<?php

declare(strict_types=1);

namespace App\Annotation;

use App\Model\User\Entity\User\User;
use App\ReadModel\User\UserFetcher;
use App\Security\UserIdentity;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class RequiresUserCreditsSubscriber implements EventSubscriberInterface
{
    /** @var Reader */
    private $reader;
    /** @var UserFetcher */
    private $users;
    /** @var Security */
    private $security;
    /** @var RedirectController */
    private $redirectController;

    public function __construct(
        Reader $reader,
        UserFetcher $users,
        Security $security,
        RedirectController $redirectController
    ) {
        $this->reader = $reader;
        $this->users = $users;
        $this->security = $security;
        $this->redirectController = $redirectController;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param ControllerEvent $event
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $target = $event->getController();

        if (!is_array($target)) {
            return;
        }

        [$controller, $method] = $target;

        if ($this->hasAnnotation($controller, $method, RequiresUserCredits::class)) {
            if (!$identity = $this->security->getUser()) {
                throw new AccessDeniedException();
            }
            if (!$identity instanceof UserIdentity) {
                throw new AccessDeniedException();
            }

            $user = $this->users->get($identity->getId());

            if (!$this->hasCredits($user)) {
                /** @var Session $session */
                $session = $event->getRequest()->getSession();
                $session->getFlashBag()->add('error', 'Set and confirm email for working.');
                $event->getRequest()->attributes->set('route', 'profile');
                $event->setController([$this->redirectController, 'redirectAction']);
            }
        }
    }

    private function hasCredits(User $user): bool
    {
        return $user->getEmail() !== null;
    }

    /**
     * @param $controller
     * @param string $method
     * @param string $class
     * @return bool
     * @throws ReflectionException
     */
    private function hasAnnotation($controller, string $method, string $class): bool
    {
        if ($this->reader->getMethodAnnotation((new ReflectionObject($controller))->getMethod($method), $class)) {
            return true;
        }
        if ($this->reader->getClassAnnotation(new ReflectionClass($controller), $class)) {
            return true;
        }
        return false;
    }
}
