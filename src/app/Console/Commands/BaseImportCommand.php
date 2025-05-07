<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

abstract class BaseImportCommand extends Command
{
    abstract protected function fileName(): string;

    abstract protected function handleData(array $items): void;

    /**
     * Padariau paprastuoju budu, nes labai mazi duomenu kiekiai.
     * Kai buna didesni duomenu kiekiai vengciau pavieniu db uzklausu, taip pat skaidyciau importa i atskirus procesus (pvz multithreading). Taip pat neuzkrauciau viso file i atminti.
     * Taip pat insertai turetu buti batchais ir tikrinimai ar prekes reikia atnaujinti - butu galima ispresti su cache ar kokiu clickhouse
     * Sukelimas siuo atveju i meilisearch irgi turetu buti batch, o ne po viena
     */
    public function handle(): void
    {
        $file = $this->fileName();

        $this->info('Starting import from ' . $file);

        try {
            $items = $this->loadJsonFile($this->fileName());
            $this->handleData($items);
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            Log::error('Import failed: ' . $e->getMessage());
            return;
        }

        $this->info('Import from ' . $file . ' completed');
    }

    /**
     * @throws \Exception
     */
    protected function loadJsonFile(string $file): array
    {
        $path = storage_path('app/public/' . $file);

        if (!file_exists($path)) {
            throw new \Exception('File not found: ' . $path);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \Exception('Invalid JSON format in file');
        }

        return $data;
    }
}

