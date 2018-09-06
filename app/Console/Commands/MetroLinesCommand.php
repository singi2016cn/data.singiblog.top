<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\MetroLines;
use App\Models\City;

class MetroLinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:metro-lines {url?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl metro-lines from http://www.szmc.net/ver2/operating/search';
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
        $file = $this->argument('url') ? $this->argument('url') : 'http://www.szmc.net/ver2/operating/search';
        $this->info("开始从网页[$file]获取数据...");
        $city_name = '深圳市';
        $client = new Client();
        $crawler = $client->request('GET', $file);
        $metro_lines = $crawler->filter('#content ul>li>a')->each(function ($node) use ($city_name) {
            $metro_line['name'] = $node->text();
            $query = explode('&',parse_url($node->attr('href'),PHP_URL_QUERY));
            $metro_line['code'] = explode('=',$query[0])[1];
            $metro_line['city_name'] = $city_name;
            return $metro_line;
        });
        if ($metro_lines){
            $this->info("获取数据成功,将写入数据库");
            $bar = $this->output->createProgressBar(count($metro_lines));
            foreach($metro_lines as $metro_line){
                MetroLines::UpdateOrCreate(['city_name'=>$city_name,'name'=>$metro_line['name']],$metro_line);
                $bar->advance();
            }
            $bar->finish();
            echo "\n";
        }
        $this->info('写入成功,任务结束.');
    }
}