<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateShortLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'isvite:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Shortlink';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $candidates = Candidate::all();

        $urls = $candidates->pluck('link')->toArray();

        $r = Http::acceptJson()->withOptions(['verify' => true])->retry(3,100)->post('https://isvite.com/api/links', [
            "urls" => $urls,
            'bulk' => true
        ]);

        if($r->ok()) {
            foreach ($r->json() as $key => $data) {
                Candidate::whereLink($data['destination'])->update(['short_link' => $data['url']]);
            }
        }
        return Command::SUCCESS;
    }
}
