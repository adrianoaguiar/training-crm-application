<?php

namespace Webinar\Bundle\SalesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebinarSalesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'OroCRMSalesBundle';
    }
}
