<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ScrapingCommand extends Command
{
    protected $signature = 'scraping:run 
                            {site : O site para fazer scraping}
                            {--limit=100 : Limite de items para coletar}';

    protected $description = 'Executa o robô de scraping';

    public function handle()
    {
        $site = $this->argument('site');
        $limit = $this->option('limit');

        $this->info("Iniciando scraping do site: {$site}");

        $scriptPath = base_path('scripts/scraper/main.py');
        
        $result = Process::timeout(300)
            ->run("python3 {$scriptPath} --site {$site} --limit {$limit}");

        if ($result->successful()) {
            $output = $result->output();
            
            // Processar o JSON retornado pelo Python
            $data = json_decode($output, true);
            
            if ($data) {
                // Salvar no banco de dados
                $this->saveToDatabase($data);
                $this->info("Scraping concluído! {$data['count']} items coletados.");
            }
        } else {
            $this->error("Erro no scraping: " . $result->errorOutput());
        }

        return Command::SUCCESS;
    }

    private function saveToDatabase(array $data)
    {
        // Implementar salvamento no banco
        // Ex: ScrapingResult::create($data);
    }
}