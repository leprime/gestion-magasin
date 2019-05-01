<?php

namespace App\EventListener;

use App\Entity\Output;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OutputListener implements EventSubscriber {

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if (!$entity instanceof Output) {
            return;
        }
        $entity->getProduit()->setQuantity($entity->getProduit()->getQuantity() - $entity->getQuantity());
    }
}