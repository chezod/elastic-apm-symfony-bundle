<?php

declare(strict_types=1);

namespace ElasticApmBundle\TransactionNamingStrategy;

use Symfony\Component\HttpFoundation\Request;

interface TransactionNamingStrategyInterface
{
    public function getTransactionName(Request $request): string;
}
