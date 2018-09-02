<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class CrawlCountryCommand extends Command
{
    public $crawl_url = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl country from url';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $goutteClient = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 20,
        ));
        $goutteClient->setClient($guzzleClient);
        $crawler = $goutteClient->request('GET', 'http://www.360doc.com/content/18/0613/08/26620346_761893039.shtml');
        $ret = $crawler
            ->filter('table.wikitable:nth-child(12) > tbody:nth-child(1) > tr:nth-child(2) > td:nth-child(2) > b:nth-child(1) > a:nth-child(1)')
            ->text();
        $this->info($ret);
    }
}
