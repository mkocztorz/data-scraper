# data-scraper
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0b73e8c2-6bbf-4c4e-a6f7-1763bf5b9fa2/big.png)](https://insight.sensiolabs.com/projects/0b73e8c2-6bbf-4c4e-a6f7-1763bf5b9fa2)

I will make it available on packagist and add more docs soon!  

## What is it?
Data scraper is based on great Symfony DomCrawler component. Symfony\Component\DomCrawler\Crawler is expected as an input to data scraper.

Data scraper focuses on the task of extracting data from HTML that is loaded into Crawler object. The extraction is done by selecting DOM element(s) with css selector and applying appropriate extraction method to it. Css selectors and extraction methods are extendable - you may add your own extensions if needed.

Data scraper allows extracting a single value or an array of values. It allows scraping a complex set of data in one sweep. The result may be a value, an array (list of items) or even nested data structures. 

## What it is not.
It is not a web spider - it doesn't do any web requests - that's up to you. It doesn't care what is the source of the HTML.

## Learn by example
For those who want to dive right into it, please visit this tutorial: (coming soon) or take a look at the example at the bottom.

## Working with data scraper
The main entry point to data scraper is \Mkocztorz\DataScraper\Extractor\ExtractorBase. This is a service class where the extraction methods are registered and used. But you're much more likely to use the \Mkocztorz\DataScraper\Std\Extractor that has all the standard extraction methods registered by default.

There is also \Mkocztorz\DataScraper\Html\SelectorProviderBase that helps to register and use the selectors. There is also a ready to use version \Mkocztorz\DataScraper\Std\SelectorProvider with the default Css selector registered. Most of the time, while working with data scraper, selector provider and selector objects will be transparent for you. The Std Extractor by default uses Std SelectorProvider.
 
You may consider working with data scraper as 2 step job:

1. Create a formula that describes where in the HTML is the data you want to scrape and what method should be used (e.g. is it element's text or attribute).

2. Apply the formula created in step 1 to the HTML. 

You don't need to create the formula for every HTML you want so scrape. If for example you want to scrape a paginated lists of items or user profiles then you only need to create the formula once and then apply it to every page of the results or user profile.

## Build in goodies
### Extraction Methods
Default extraction methods are in \Mkocztorz\DataScraper\Extractor\Method namespace.
Examples below assume creating Extractor service first:
```php
use Mkocztorz\DataScraper\Std\Extractor;
$extractor = new Extractor();
```
Examples below show step 1 of the process: creating the formula. When you want to extract the data you need to have the Crawler with HTML loaded and call:
 ```php
 $data = $extractor->extract($crawler); //step 2 - the actual scraping
 ```

#### Element's text
Class: ExtractElementText

Registered as: text

Extractor method: getText

Params: none

Usage:
```php
$extractor->getText('#title');
```

Will: get the text from element with id="title".

Note: It will use the first element found using css selector.
 
If element not found: returns empty string.

#### Element's attribute value
Class: ExtractAttribute

Registered as: attribute

Extractor method: getAttribute

Params: ['attr'=>attribute name]

Usage:
```php
$extractor->getAttribute('#title', ['attr'=>'age']);
```

Will: get the age attribute value from element with title ID.

Note: It will use the first element found using css selector.
 
If element or attribute not found: returns empty string.

#### List of elements
**NOTE: This extraction method is different from the previous ones**

Class: ExtractList

Registered as: list

Extractor method: getList

Params: Array of key-value pairs. Each value must be another Extraction Method. Key will be used as key in result item.

This extraction method works differently than the previous ones. It is designed to scrape data from lists. By itself ExtractList doesn't actually scrape any data but it uses the ExtractorMethods on each element found by selector to scrape data. Think of it as a kind of **foreach** control structure. **Important** every ExtractorMethod used by ExtractList gets a Crawler that contains only one element found by ExtractorList selector (think of it as a kind of namespace). The result is that child ExtractMethod selector will apply only to that element.   


Usage:
```php
$extractor->getList('ul li', [
    'name' => $extractor->getText('h1'),    // actually selecting: "ul li h1"
    'age'  => $extractor->getText('p'),     // actually selecting: "ul li p"
    'id'   => $extractor->getAttribute('', ['attr'=>'id']), // actually selecting "ul li", assuming that each li will have id attribute
]);
```

Will: Get every list item found by "ul li" selector and pass it to each of the ExtractMethods, that in turn will do their job. Sample result might look like:
``` php
[
    [
        'name'  => 'John',
        'age'   => '15',
        'id'    => 'user-345',
    ],
    [
        'name'  => 'Alice',
        'age'   => '37',
        'id'    => 'user-33',
    ]
]
```

Note: In the params **you may use** $extract->getList(..) again!
 
If list is empty: None of child ExtractMethods are executed and the result is empty array.

## Under the hood
How it works and how it can be extended to your needs. 


More docs coming soon.

## Example
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

## Licence MIT
