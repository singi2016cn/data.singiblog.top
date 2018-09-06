<?php

namespace App\Console\Commands;

use App\Models\MetroLines;
use App\Models\MetroStations;
use App\Models\MetroStationsExits;
use Illuminate\Console\Command;
use Goutte\Client;

class MetroStationExitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:metro-station-exits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl metro_station_exits from http://www.szmc.net/ver2/operating/search';

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
        $start_time = time();
        $this->info('start');
        $client = new Client();
        $metro_stations = MetroStations::all();
        foreach($metro_stations as $k=>$metro_station){
            $metro_line_code = MetroLines::where('id',$metro_station->metro_line_id)->value('code');
            $xl = $metro_line_code[1];
            $this->info('--- station:'.$metro_station->name.':'.$metro_station->id.' ---');
            $url = 'http://www.szmc.net/ver2/operating/search?scode='.$metro_station->code.'&xl='.$xl;
            $this->info($url);
            $crawler = $client->request('GET', $url);
            $metro_station_exits_name = $crawler->filter('.gate_right tbody>tr>th')->each(function ($node) {
                return $node->text();
            });
            $metro_station_exits_note = $crawler->filter('.gate_right tbody>tr>td')->each(function ($node) {
                return $node->text();
            });
            foreach($metro_station_exits_name as $k=>$v){
                $metro_station_exits['metro_stations_id'] = $metro_station->id;
                $name = str_replace(['出口','：',':','）','所在口',"\n","\t"],'',$v);
                $name_arr = explode('（',$name);
                $metro_station_exits['name'] = $name_arr[0];
                $metro_station_exits['has_elevator'] = 2;
                if (isset($name_arr[1]) && preg_match('/垂梯/',$name_arr[1])){
                    $metro_station_exits['has_elevator'] = 1;
                }
                $metro_station_exits['has_wc'] = 2;
                if (isset($name_arr[1]) && preg_match('/洗手间/',$name_arr[1])){
                    $metro_station_exits['has_wc'] = 1;
                }
                $metro_station_exits['note'] = str_replace(['出口','：',':',"\n","\t"],'',$metro_station_exits_note[$k]);
                MetroStationsExits::updateOrCreate(['metro_stations_id'=>$metro_station_exits['metro_stations_id'],'name'=>$metro_station_exits['name']],$metro_station_exits);
                $this->info('insert '.$metro_station_exits['name']);
            }
        }
        $this->info('done, total cost '.(time()-$start_time).' sec');
    }
}
