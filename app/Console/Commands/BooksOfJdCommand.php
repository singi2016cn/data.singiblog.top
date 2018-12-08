<?php

namespace App\Console\Commands;

use App\Models\Books;
use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class BooksOfJdCommand extends Command
{
    const INIT_ITEM_ID = 11000000;//初始item_id
    const RESOURCE_URL = 'https://item.jd.com/ITEM_ID.html';//资源url
    const RESOURCE_MORE_INFO_URL = 'https://dx.3.cn/desc/ITEM_ID?cdn=2&callback=showdesc';//更多信息资源url
    //详细信息下标转换
    const ZH2EN = [
        '出版社' => 'publishing_company',
        'ISBN' => 'isbn',
        '版次' => 'edition',
        '商品编码' => 'commodity_code',
        '包装' => 'packing',
        '丛书名' => 'series_name',
        '开本' => 'format',
        '出版时间' => 'publish_date',
        '用纸' => 'form',
        '页数' => 'page',
        '正文语种' => 'language',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:jd_books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl books of Jd';

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
            'timeout' => 0,
        ));
        $goutteClient->setClient($guzzleClient);

        while (1){
            $item_id = $this->getItemId();
            if ($item_id>20000000){
                break;
            }
            $book = $this->getBook($goutteClient,$item_id);
            $this->info(' finished! crawl book id:'.$item_id);
            Books::updateOrCreate(['title'=>$book['title']],$book);
        }
    }

    function getBook($goutteClient,$item_id){
        $resource_url = str_replace('ITEM_ID', $item_id, self::RESOURCE_URL);
        $book['resource_url'] = $resource_url;
        $crawler = $goutteClient->request('GET', $resource_url);
        //获取主要信息
        if ($crawler->filter('#name > div.sku-name')->count() <= 0) {
            return null;
        }
        $book['title'] = trim($crawler->filter('#name > div.sku-name')->text());
        $book['author'] = trim($crawler->filter('#p-author')->text());
        $book['cover'] = trim($crawler->filter('#spec-n1 > img')->image()->getUri());
        $book_categories = $crawler->filter('#crumb-wrap > div > div.crumb.fl.clearfix > div')->each(function ($node) {
            return trim($node->text());
        });
        if ($book_categories) $book['category'] = implode('', $book_categories);
        //获取详细信息
        $book_param = $crawler->filter('#parameter2 > li')->each(function ($node) {
            return $node->text();
        });
        if ($book_param) {
            foreach ($book_param as $v) {
                $v_arr = explode('：', $v);
                if (isset($v_arr[0]) && isset(self::ZH2EN[$v_arr[0]])) $book[self::ZH2EN[$v_arr[0]]] = trim($v_arr[1]);
            }
        }
        //更多信息
        $resource_more_file_url = str_replace('ITEM_ID', $item_id, self::RESOURCE_MORE_INFO_URL);
        $book['resource_more_file_url'] = $resource_more_file_url;
        $crawler_more_info = $goutteClient->request('GET', $resource_more_file_url);
        $book_more_info = $goutteClient->getInternalResponse()->getContent();
        if ($book_more_info) {
            $book_more_info_html = json_decode(mb_convert_encoding(substr($book_more_info, 9, -1),'utf-8','gbk'), true)['content'];
        }
        if ($book_more_info_html) {
            $crawler_more_info->addHtmlContent($book_more_info_html);
            $book['content_profile'] = $this->getText($crawler_more_info, '#detail-tag-id-3 div.book-detail-content');
            $book['author_profile'] = $this->getText($crawler_more_info, '#detail-tag-id-4 div.book-detail-content');
            $book['table_of_contents'] = $this->getText($crawler_more_info, '#detail-tag-id-6 div.book-detail-content');
        }
        return $book;
    }

    /**
     * 防止致命错误,命令中断
     * @param $crawler
     * @param $selecter
     * @return null|string
     */
    function getText($crawler, $selecter)
    {
        $crawler_filter = $crawler->filter($selecter);
        if ($crawler_filter->count()) {
            return trim($crawler_filter->text());
        } else {
            return null;
        }
    }

    function getItemId(){
        $book_id = Books::orderBy('id','desc')->value('id');
        if ($book_id > 0){
            return $book_id+self::INIT_ITEM_ID;
        }else{
            return self::INIT_ITEM_ID;
        }
    }
}
