<?php
/**
 * User: kis龍 <270228163@qq.com>
 * Date: 2019-9-17 11:24
 */

namespace Pifeifei;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class HtmlParser
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Crawler
     */
    protected $crawler;

    protected static $instance = null;

    /**
     * @var string[]
     */
    protected $attributes = [];

    protected $html = '';

    public function __construct()
    {
        $this->client = new Client([
            'http_errors' => false,
            'cookies' => true,
            'timeout' => 10,
            'headers' => ['User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36']
        ]);
    }

    public function get($url, $options = [])
    {
        $response = $this->client->request('GET', $url, $options);
        $this->crawler = new Crawler('', $url);
        $this->clear();

        $this->html = $this->removeNotes($this->removeTag($response->getBody()->getContents()));
        $this->htmlConvertEncodingToUTF8();

        return $this;
    }


    /**
     * 获取属性
     *
     * @return array
     */
    public function getAttributes()
    {
        $this->getTitle();
        $this->getContent();

        return $this->attributes;
    }

    /**
     * 获取标题
     * @return string
     */
    public function getTitle()
    {
        if(! empty($this->attributes['title'])){
            return $this->attributes['title'];
        }

        $tmp = $this->crawler->filter('title');
        $lists = $this->crawler->filter('h1,h2,h3');
        if($tmp->count()){
            $headTitle = $tmp->eq(0)->text();
        } else {
            $headTitle = '';
        }
        unset($tmp);

        $this->attributes['title'] = '';
        foreach ($lists as $key => $h){
            $title = trim($lists->eq($key)->text());
            if(false !== strpos($lists->eq($key)->html(), $title)){
                $this->attributes['title'] = $title;
                $pos = strpos($headTitle, $title);
                if(false !== $pos && $pos < strlen($headTitle)/2){
                    break;
                }
            }
        }

        return $this->attributes['title'];
    }

    /**
     * @return string
     */
    /**
     * @param string $removeFilters 删除的元素, 多个用逗号分隔
     * @return string
     */
    public function getContent($removeFilters = '')
    {
        if(! empty($this->attributes['content'])){
            return $this->attributes['content'];
        }

        $lists = $this->crawler->filter('div>section, div>p');
        $this->attributes['content'] = '';
        $currentDOM = ['domKey'=>-1, 'count'=>0] ;
        foreach ($lists as $key => $h){
            $count = $lists->eq($key)->siblings()->count();

            if($lists->getNode($key)->nodeName === 'section'){
                $count += $lists->eq($key)->filter('section, p')->count();
            }
            if($count > $currentDOM['count']){
                $currentDOM['domKey'] = $key;
                $currentDOM['count'] = $count;
            }
        }

        if(! empty($removeFilters)){
            $lists->eq($currentDOM['domKey'])->parents()->filter($removeFilters)->each(function($nodes){
                foreach($nodes as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
        }

        if($currentDOM['domKey']>-1){
            $this->attributes['content'] = trim($lists->eq($currentDOM['domKey'])->parents()->html());
        }

        return $this->attributes['content'];
    }

    /**
     * 删除标签
     *
     * @param string $html
     * @param array $tags
     * @return string|string[]|null
     */
    protected function removeTag($html = '', $tags = ['script', 'style'])
    {
        foreach ($tags as $tag){
            $pattern = "/<({$tag})[^>]*>(?:.*?)<\/{$tag}>/is";
            $html = preg_replace($pattern, '', $html);
        }

        return $html;
    }

    /**
     * 删除 HTML 注释
     *
     * 如:  <!-- 我是注释, 要删除 -->
     *
     * @param $html
     * @return string|string[]|null
     */
    protected function removeNotes($html)
    {
        return preg_replace("/\<\!--(?:.*?)--\>/i", '', $html);
    }

    private function clear()
    {
        $this->attributes = [];
        $this->html = '';
    }

    private function htmlConvertEncodingToUTF8()
    {
        $this->crawler->clear();

        // html 编码转换
        $detectEncoding = mb_detect_encoding($this->html, ['UTF-8', 'GBK', 'GB2312', 'ASCII']);
        if('UTF-8' !== $detectEncoding){
            $this->html = mb_convert_encoding($this->html, 'UTF-8', $detectEncoding);
        }

        // http://www.w3.org/TR/encoding/#encodings
        // http://www.w3.org/TR/REC-xml/#NT-EncName
        // meta 标签转换
        if (preg_match('/\<meta[^\>]+charset *= *["\']?([a-zA-Z\-0-9_:.]+)/i', $this->html, $matches)) {
            $charset = $matches[1];

            $this->html = str_replace(
                $matches[0],
                str_replace($charset, 'UTF-8', $matches[0]),
                $this->html
            );
        }

        $this->crawler->addHtmlContent($this->html);

    }
}
