UrlObject
===========
The UrlObject class is a helper class for when working with URLs.

Example usage:

    use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;

    $url = new UrlObject('http://www.webiny.com/');

    $url->setPath('search')->setQuery(['q'=>'some string']); // http://www.webiny.com/search?q=some+string