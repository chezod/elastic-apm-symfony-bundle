<?php

declare(strict_types=1);

namespace ElasticApmBundle;

use ElasticApmBundle\Listener\DeprecationListener;
use ElasticApmBundle\Listener\WarningListener;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElasticApmBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        if ($this->container->has(DeprecationListener::class)) {
            $this->container->get(DeprecationListener::class)->register();
        }

        if ($this->container->has(WarningListener::class)) {
            $this->container->get(WarningListener::class)->register();
        }
    }

    public function shutdown()
    {
        if ($this->container->has(DeprecationListener::class)) {
            $this->container->get(DeprecationListener::class)->unregister();
        }

        if ($this->container->has(WarningListener::class)) {
            $this->container->get(WarningListener::class)->unregister();
        }

        parent::shutdown();
    }
}
