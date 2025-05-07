<?php

namespace App\Console\Commands;

use App\Services\ProductStockService;

class ImportStock extends BaseImportCommand
{
    protected $signature = 'import:stock';
    protected $description = 'Import product stock from file';

    public function __construct(protected ProductStockService $service)
    {
        parent::__construct();
    }

    protected function fileName(): string
    {
        return 'stocks.json';
    }

    protected function handleData(array $items): void
    {
        $this->service->import($items);
    }
}
