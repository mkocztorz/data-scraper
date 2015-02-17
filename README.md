# data-scraper
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0b73e8c2-6bbf-4c4e-a6f7-1763bf5b9fa2/big.png)](https://insight.sensiolabs.com/projects/0b73e8c2-6bbf-4c4e-a6f7-1763bf5b9fa2)

The goal of this library is to simplify data scraping from HTML. The result is an array of values.

It doesn't do any web requests - that's up to you. 

More docs coming soon. Just code example for now:

``` php
use Mkocztorz\DataScraper\Std\Extractor;
use Symfony\Component\DomCrawler\Crawler;

$crawler = new Crawler();
$crawler->addHtmlContent(file_get_contents('Tests/Resources/PlainHtml/test1.html'));

$extractor = new Extractor(); //a service

// creating the scraping pattern - what and how you want to scrape
$res = $extractor->getList('ul li', [       // select all li elements and in each of them:
    'name' => $extractor->getText('h1'),    // find first h1 and take its content
    'position' => $extractor->getText('p'), // find first p and take its content
                                    // find first h1 and take its age attribute
    'age' => $extractor->getAttribute('h1', ['attr'=>'age']),
                                    // find a list of elements that have .exp class and in each of them
    'experience' => $extractor->getList('.experience .exp', [
        'exp' => $extractor->getText()      // get the contents of top level element (.exp)
    ]),
])->extract($crawler); // extract the actual data by applying the pattern to html
// you may extract data from multiple Crawlers with one pattern (e.g. paginated resource)
```

The result:
```
Array
(
    [0] => Array
        (
            [name] => John Doe
            [position] => expert
            [age] => 23
            [experience] => Array
                (
                    [0] => Array
                        (
                            [exp] => exp 1
                        )

                    [1] => Array
                        (
                            [exp] => exp 2
                        )

                    [2] => Array
                        (
                            [exp] => exp 3
                        )

                    [3] => Array
                        (
                            [exp] => exp 4
                        )

                )

        )

    [1] => Array
        (
            [name] => Jane Doe
            [position] => client
            [age] =>
            [experience] => Array
                (
                    [0] => Array
                        (
                            [exp] => AAA
                        )

                    [1] => Array
                        (
                            [exp] => BBB
                        )

                )

        )

)

```
