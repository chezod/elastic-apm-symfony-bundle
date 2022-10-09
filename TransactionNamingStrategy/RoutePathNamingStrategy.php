<?php

declare(strict_types=1);

namespace ElasticApmBundle\TransactionNamingStrategy;

use Symfony\Component\HttpFoundation\Request;

class RoutePathNamingStrategy implements TransactionNamingStrategyInterface
{
    public function getTransactionName(Request $request): string
    {
        $parameters = $request->attributes->get('_route_params', []);

        $path = $parameters['path'] ?? '';

        if ('' !== $path) {
            return "{$request->getMethod()} {$path}";
        }

        return "{$request->getMethod()} {$request->getRequestUri()}";
    }
}
