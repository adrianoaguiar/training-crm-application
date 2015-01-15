<?php

namespace Webinar\Bundle\DemoBundle\EventListener;

use Doctrine\ORM\EntityManager;

use OroCRM\Bundle\ContactBundle\EventListener\ContactListener;

class CreatedUpdatedListener extends ContactListener
{
    /**
     * @param object        $entity
     * @param EntityManager $entityManager
     */
    protected function setCreatedProperties($entity, EntityManager $entityManager)
    {
        if (!$entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }
        if (!$entity->getCreatedBy()) {
            $entity->setCreatedBy($this->getUser($entityManager));
        }
    }

    /**
     * @param object        $entity
     * @param EntityManager $entityManager
     * @param bool          $update
     */
    protected function setUpdatedProperties($entity, EntityManager $entityManager, $update = false)
    {
        $newUpdatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $newUpdatedBy = $this->getUser($entityManager);

        $unitOfWork = $entityManager->getUnitOfWork();
        if ($update) {
            $unitOfWork->propertyChanged($entity, 'updatedAt', $entity->getUpdatedAt(), $newUpdatedAt);
            $unitOfWork->propertyChanged($entity, 'updatedBy', $entity->getUpdatedBy(), $newUpdatedBy);
            $entity->setUpdatedAt($newUpdatedAt);
            $entity->setUpdatedBy($newUpdatedBy);
        } else {
            if (!$entity->getUpdatedAt()) {
                $entity->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
            }
            if (!$entity->getUpdatedBy()) {
                $entity->setUpdatedBy($newUpdatedBy);
            }
        }
    }
}
