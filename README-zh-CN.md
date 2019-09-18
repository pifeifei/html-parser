# HTML 网页提取标题和正文

### 安装：

```shell
composer require pifeifei/html-parser
```




### 使用：


```php
use Pifeifei\HtmlParser;

$htmlParser = new HtmlParser();
$url = 'http://baijiahao.baidu.com/s?id=1644885607674637957';

$htmlParser->get($url);

dump($this->htmlParser->getTitle());
dump($this->htmlParser->getContent());
```



### 说明：

> 仅供正常学习使用，请勿用作非法用途。一切后果自行承担，与开发者无关。