<?php

namespace App\Console\Commands;

use App\Services\ProductService;

class ImportProducts extends BaseImportCommand
{
    protected $signature = 'import:products';
    protected $description = 'Import products from file';

    public function __construct(protected ProductService $service)
    {
        parent::__construct();
    }

    protected function fileName(): string
    {
        return 'products.json';
    }

    protected function handleData(array $items): void
    {
        $this->service->import($items);
    }
}

