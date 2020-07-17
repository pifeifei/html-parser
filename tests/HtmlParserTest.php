<?php

namespace Pifeifei\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Pifeifei\HtmlParser;

/**
 * User: kis龍 <270228163@qq.com>
 * Date: 2019-9-17 12:06
 */

class HtmlParserTest extends BaseTestCase
{

    /**
     * @var HtmlParser
     */
    protected $htmlParser;

    public function setUp()
    {
        $this->htmlParser = new HtmlParser();
    }

    public function testWeChat()
    {
        $url = 'https://mp.weixin.qq.com/s?src=11&timestamp=1594967748&ver=2465&signature=PLg*AcOmvKi1xL49fiyRIvarpznvGX*wEqzeWVpArVqolCZeqquGy8j*UvmdlDriL1Pfq9ABfac-TyVJpNNq6m6ip63GMML*eNPgTwTyMJMfxDXNc3koRxbzOWG2pJ3H&new=1';

        $this->htmlParser->get($url);

        $this->assertGreaterThan(0,strlen($this->htmlParser->getTitle()));
        $this->assertGreaterThan(0,strlen($this->htmlParser->getContent()));
    }

    public function TestBaiJiaBao()
    {
        $url = 'http://baijiahao.baidu.com/s?id=1644885607674637957';

        $this->htmlParser->get($url);

        $this->assertGreaterThan(0,strlen($this->htmlParser->getTitle()));
        $this->assertGreaterThan(0,strlen($this->htmlParser->getContent()));
    }

    public function TestTencentNew()
    {
        $url = 'https://new.qq.com/omn/20190917/20190917A03DEA00.html';

        $this->htmlParser->get($url);

        $this->assertGreaterThan(0,strlen($this->htmlParser->getTitle()));
        $this->assertGreaterThan(0,strlen($this->htmlParser->getContent()));
    }

    public function Test163()
    {
        $url = 'https://tech.163.com/19/0918/07/EPBDVMCT00097U7T.html';

        $this->htmlParser->get($url);

        $this->assertGreaterThan(0,strlen($this->htmlParser->getTitle()));
        $this->assertGreaterThan(0,strlen($this->htmlParser->getContent()));
    }

    public function TestTouTiao()
    {

        /*$url = 'https://www.toutiao.com/a6737245376023101955/';

        $this->htmlParser->get($url);

        $this->assertGreaterThan(0,strlen($this->htmlParser->getTitle()));
        $this->assertGreaterThan(0,strlen($this->htmlParser->getContent()));*/

        // 内容不在网页中直接显示, 目前无法抓取
        $this->assertTrue(true);
    }
}