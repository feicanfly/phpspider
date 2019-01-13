<?php
// composer下载方式
// 先使用composer命令下载：
// composer require owner888/phpspider
// 引入加载器
//require './vendor/autoload.php';

// GitHub下载方式
require_once __DIR__ . '/../autoloader.php';
use phpspider\core\phpspider;

/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => 'zhihu',
    'log_show' => true,
    'tasknum' => 1,
    'save_running_state' => true,
    'domains' => array(
        'zhihu.com',
        'www.zhihu.com'
    ),
    'scan_urls' => array(
        'https://www.zhihu.com/people/wang-peng-fei/following'
    ),
    'list_url_regexes' => array(
        "https://www.zhihu.com/people/[a-zA-z0-9-]*",
    ),
    'content_url_regexes' => array(
        "https://www.zhihu.com/people/.*/following",
        "https://www.zhihu.com/people/.*/followers",
    ),
    'max_try' => 5,

    'export' => array(
        'type' => 'db', 
        'table' => 'user',
    ),
    'db_config' => array(
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'homestead',
        'pass'  => 'secret',
        'name'  => 'zhihu',
    ),
    'queue_config' => array(
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'pass'      => '',
        'db'        => 5,
        'prefix'    => 'phpspider',
        'timeout'   => 30,
    ),
    'fields' => array(
        array(
            'name' => "name",
            //*[@id="ProfileHeader"]/div/div[2]/div/div[2]/div[1]/h1/span[1]
            'selector' => "//*[@id='ProfileHeader']/div/div[2]/div/div[2]/div[1]/h1/span[1]/text()",
            'required' => true,
        ),
        array(
            'name' => "url",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[2]/div/a/@href",
            'required' => true,
        ),

        array(
            'name' => "title",
            'selector' => "//*[@id='ProfileHeader']/div/div[2]/div/div[2]/div[1]/h1/span[2]",
        ),

        array(
            'name' => "following",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[2]/div/a[1]/div/strong",
        ),
        array(
            'name' => "followers",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[2]/div/a[2]/div/strong",
        ),
        array(
            'name' => "star",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[1]/div[2]/div[1]/div[1]",
        ),
        array(
            'name' => "favo",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[1]/div[2]/div[1]/div[2]",
        ),
        array(
            'name' => "live",
            'selector' => "//*[@id='root']/div/main/div/div[2]/div[2]/div[3]/a[1]/span[2]",
        ),
        array(
            'name' => "answer",
            'selector' => "//*[@id='ProfileMain']/div[1]/ul/li[2]/a/span",
        ),
        array(
            'name' => "ask",
            'selector' => "//*[@id='ProfileMain']/div[1]/ul/li[3]/a/span",
        ),
        array(
            'name' => "posts",
            'selector' => "//*[@id='ProfileMain']/div[1]/ul/li[4]/a/span",
        ),
        array(
            'name' => "columns",
            'selector' => "//*[@id='ProfileMain']/div[1]/ul/li[5]/a/span",
        ),
        array(
            'name' => "pins",
            'selector' => "//*[@id='ProfileMain']/div[1]/ul/li[6]/a/span",
        ),


    ),
);

$spider = new phpspider($configs);

$spider->on_fetch_url = function($url, $phpspider) 
{
    $arr = ["activities",
        "answers",
        "asks",
        "posts",
        "columns",
        "pins",
        // "following",
        "followers",
        "topics",
        "questions",
        "logs",
        "creations",
        "collections",
        // "columns",
        ];

    foreach ($arr as $key => $value) {
        if (strpos($url, $value) !== false)
        {
            return false;
        }
    }

    return $url;
};


$spider->on_extract_field = function($fieldname, $data, $page) 
{
    if ($fieldname == 'url') 
    {
        $data = str_replace('/people/', '', $data);
        return str_replace('/following', '', $data);
    }
    return $data;
};


$spider->on_start = function($phpspider) 
{



};



$spider->start();


