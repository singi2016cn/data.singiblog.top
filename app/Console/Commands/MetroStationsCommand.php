<?php

namespace App\Console\Commands;

use App\Models\MetroLines;
use App\Models\MetroStations;
use Goutte\Client;
use Illuminate\Console\Command;

class MetroStationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:metro-stations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl metro_stations from http://www.szmc.net/public/scripts/sites.js';

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
        $this->info('start');
        $client = new Client();
        $crawler = $client->request('get','http://www.szmc.net/public/scripts/sites.js');
        $r = $client->getResponse();
        $js_content = $r->getContent();
        preg_match_all('/\[.+\]/',$js_content,$js_sites);
        $site_lines = json_decode(str_replace('\'',"\"",$js_sites[0][8]));
        foreach($js_sites[0] as $js_site){
            $js_site_ret = preg_replace(["/([a-zA-Z_]+[a-zA-Z0-9_]*)\s*:/", "/:\s*'(.*?)'/"], ['"\1":', ': "\1"'], $js_site);
            $sites[] = json_decode($js_site_ret,true);
        }
        $sites = array_filter($sites);
        foreach($sites as $k=>$site){
            $site_line = $site_lines[$k];
            $metro_station['metro_lines_id'] = MetroLines::where('name',$site_line)->value('id');
            $this->info('--- metro-line:'.$site_line.$metro_station['metro_lines_id'].' ---');
            foreach($site as $v){
                $metro_station['name'] = $v['n'];
                $metro_station['code'] = $v['c'];
                MetroStations::updateOrCreate(['name'=>$metro_station['name']],$metro_station);
                $this->info('insert '.$v['n']);
            }
        }
        $this->info('done');
    }
}
