<?php

namespace OroTraining\Bundle\AccountBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OroTrainingAccountBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'OroCRMAccountBundle';
    }
}
