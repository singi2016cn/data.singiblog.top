<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Province;
use App\Models\City;
use App\Models\County;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class CrawlPccController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:pcc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl province,city,county from http://www.mca.gov.cn';

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
        $file = 'http://www.mca.gov.cn/article/sj/xzqh/2018/201804-12/20180708230813.html';

        $this->info("开始从网页[$file]获取数据...");
        $client = new Client();
        $crawler = $client->request('GET', $file);
        $data = $crawler->filter('tr[height="19"]>td.xl708733')->each(function ($node) {
            return $node->text();
        });
        $data_chunk = array_chunk(array_filter($data), 2);
        $this->info("获取数据成功");
        $current_province = [];
        $current_city = [];
        $country_id = Country::where('short_name','中国')->value('id');
        //获取所有省市县
        if ($data_chunk) {
            foreach ($data_chunk as $k => $v) {
                $v_province_prefix = substr($v[0], 0, 2);
                if (preg_match('/' . $v_province_prefix . '0{4}/', $v[0])) {
                    $province['code'] = $v[0];
                    $province['name'] = $v[1];
                    $province['code_prefix'] = $v_province_prefix;
                    $current_province = $province;
                    $provinces[] = $province;
                } else if (preg_match('/' . $current_province['code_prefix'] . '\d{2}0{2}/', $v[0]) || in_array($v_province_prefix, [11, 12, 31, 50])) {
                    $city['code'] = $v[0];
                    $city['name'] = $v[1];
                    $city['province_code'] = $current_province['code'];
                    $current_city = $city;
                    $cities[] = $city;
                } else {
                    $county['code'] = $v[0];
                    $county['name'] = $v[1];
                    $county['city_code'] = $current_city['code'];
                    $counties[] = $county;
                }
            }
        }
        $this->info('写入 省 进数据库开始');
        if ($provinces) {
            $bar = $this->output->createProgressBar(count($provinces));
            foreach ($provinces as $v_province) {
                unset($v_province['code_prefix']);
                $v_province['country_id'] = $country_id;
                $v_province['initial'] = ucfirst(Pinyin::abbr($v_province['name']))[0];
                Province::updateOrCreate(['name'=>$v_province['name']],$v_province);
                $bar->advance();
            }
            $bar->finish();
            echo "\n";
        }
        $this->info('写入 市 进数据库开始');
        if ($cities) {
            $bar = $this->output->createProgressBar(count($cities));
            foreach ($cities as $v_city) {
                $v_city['province_id'] = Province::where('code',$v_city['province_code'])->value('id');
                unset($v_city['province_code']);
                City::updateOrCreate(['name'=>$v_city['name']],$v_city);
                $bar->advance();
            }
            $bar->finish();
            echo "\n";
        }
        $this->info('写入 县 进数据库开始');
        if ($counties) {
            $bar = $this->output->createProgressBar(count($counties));
            foreach ($counties as $v_county) {
                $v_county['city_id'] = City::where('code',$v_county['city_code'])->value('id');
                unset($v_county['city_code']);
                County::updateOrCreate(['name'=>$v_county['name']],$v_county);
                $bar->advance();
            }
            $bar->finish();
            echo "\n";
        }
        $this->info("省市县所有任务完成");
    }
}
