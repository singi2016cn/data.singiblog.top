<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\City;
use App\Models\County;
use App\Models\Street;

class CstreetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:street';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl street from http://www.stats.gov.cn';

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
        $client = new Client();

        $city_filter = ['北京市','天津市','重庆市','上海市'];

        //获取所有省
        $crawler_provinces = $client->request('GET', 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017');
        $provinces = $crawler_provinces->filter('tr.provincetr > td')->each(function ($node) {
            return $node->text();
        });
        $provinces = array_filter($provinces);
        $this->info('start collect street ');
        if ($provinces){
            foreach ($provinces as $province){
                //获取该省所属市
                $crawler_cities = $client->click($crawler_provinces->selectLink($province)->link());
                $cities = $crawler_cities->filter('tr.citytr > td')->each(function ($node) {
                    return $node->text();
                });
                $cities = array_chunk(array_filter($cities),2);
                if ($cities){
                    foreach($cities as $city){
                        $crawler_counties = $client->click($crawler_cities->selectLink($city[0])->link());
                        $counties = $crawler_counties->filter('tr.countytr > td')->each(function ($node) {
                            return $node->text();
                        });
                        $counties = array_chunk(array_filter($counties),2);
                        if ($counties){
                            foreach($counties as $county){
                                dump($county[1]);
                                if ($county[1] == '市辖区' || $county[1] == '金门县'){
                                    continue;
                                }
                                $crawler_towns = $client->click($crawler_counties->selectLink($county[0])->link());
                                $towns = $crawler_towns->filter('tr.towntr > td')->each(function ($node) {
                                    return $node->text();
                                });
                                $towns = array_chunk(array_filter($towns),2);

                                if ($towns){
                                    foreach($towns as $town){
                                        if (in_array($province,$city_filter)){
                                            $counties_insert['city_id'] = City::where('name',$county[1])->value('id');
                                            $counties_insert['name'] = $town[1];
                                            $counties_insert['code'] = $town[0];
                                            County::updateOrCreate(['name'=>$counties_insert['name']],$counties_insert);
                                            $this->info('write streets'.$town[1]);
                                        }else{
                                            $streets_insert['county_id'] = County::where('name',$county[1])->value('id');
                                            $streets_insert['name'] = $town[1];
                                            $streets_insert['code'] = $town[0];
                                            Street::updateOrCreate(['name'=>$streets_insert['name']],$streets_insert);
                                            $this->info('write streets'.$town[1]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->info('write streets success');
    }
}
