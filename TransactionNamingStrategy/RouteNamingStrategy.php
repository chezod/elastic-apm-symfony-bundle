<?php

declare(strict_types=1);

namespace ElasticApmBundle\TransactionNamingStrategy;

use Symfony\Component\HttpFoundation\Request;

class RouteNamingStrategy implements TransactionNamingStrategyInterface
{
    public function getTransactionName(Request $request): string
    {
        return $request->get('_route') ?: 'Unknown Symfony route';
    }
}
