<?php

namespace Webinar\Bundle\DemoBundle\EventListener;

use Doctrine\ORM\EntityManager;

use OroCRM\Bundle\ContactBundle\Entity\Contact;
use OroCRM\Bundle\ContactBundle\EventListener\ContactListener;

class CreatedUpdatedListener extends ContactListener
{
    /**
     * @param Contact       $contact
     * @param EntityManager $entityManager
     */
    protected function setCreatedProperties(Contact $contact, EntityManager $entityManager)
    {
        if (!$contact->getCreatedAt()) {
            $contact->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }
        if (!$contact->getCreatedBy()) {
            $contact->setCreatedBy($this->getUser($entityManager));
        }
    }

    /**
     * @param Contact       $contact
     * @param EntityManager $entityManager
     * @param bool          $update
     */
    protected function setUpdatedProperties(Contact $contact, EntityManager $entityManager, $update = false)
    {
        $newUpdatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $newUpdatedBy = $this->getUser($entityManager);

        $unitOfWork = $entityManager->getUnitOfWork();
        if ($update) {
            $unitOfWork->propertyChanged($contact, 'updatedAt', $contact->getUpdatedAt(), $newUpdatedAt);
            $unitOfWork->propertyChanged($contact, 'updatedBy', $contact->getUpdatedBy(), $newUpdatedBy);
            $contact->setUpdatedAt($newUpdatedAt);
            $contact->setUpdatedBy($newUpdatedBy);
        } else {
            if (!$contact->getUpdatedAt()) {
                $contact->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
            }
            if (!$contact->getUpdatedBy()) {
                $contact->setUpdatedBy($newUpdatedBy);
            }
        }
    }
}
