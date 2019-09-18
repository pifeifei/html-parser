# HTML Web Page Extraction Title and Text

### install:

```shell
composer require pifeifei/html-parser
```




### use:


```php
use Pifeifei\HtmlParser;

$htmlParser = new HtmlParser();
$url = 'http://baijiahao.baidu.com/s?id=1644885607674637957';

$htmlParser->get($url);

dump($this->htmlParser->getTitle());
dump($this->htmlParser->getContent());


```



### explain:

> For normal study only, do not use for illegal purposes. All consequences are borne by the developer, not by the developer.

