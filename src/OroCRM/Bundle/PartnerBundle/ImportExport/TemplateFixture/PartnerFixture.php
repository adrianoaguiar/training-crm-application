<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

use OroCRM\Bundle\PartnerBundle\Entity\Partner;
use OroCRM\Bundle\PartnerBundle\Entity\PartnerStatus;

class PartnerFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'OroCRM\Bundle\PartnerBundle\Entity\Partner';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Smith');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Partner();
    }

    /**
     * @param string  $key
     * @param Partner $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $accountRepo = $this->templateManager
            ->getEntityRepository('OroCRM\Bundle\AccountBundle\Entity\Account');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'Smith':
                $entity
                    ->setId(1)
                    ->setPartnerCondition('Aenean commodo ligula eget dolor')
                    ->setStartDate(new \DateTime())
                    ->setStatus(new PartnerStatus('active'))
                    ->setAccount($accountRepo->getEntity($key))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'));

                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
