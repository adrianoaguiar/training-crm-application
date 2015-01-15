<?php

namespace Webinar\Bundle\AccountBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebinarAccountBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'OroCRMAccountBundle';
    }
}
