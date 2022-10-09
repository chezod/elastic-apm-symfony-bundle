<?php

declare(strict_types=1);

namespace ElasticApmBundle\TransactionNamingStrategy;

use Symfony\Component\HttpFoundation\Request;

class UriNamingStrategy implements TransactionNamingStrategyInterface
{
    public function getTransactionName(Request $request): string
    {
        return "{$request->getMethod()} {$request->getRequestUri()}";
    }
}
