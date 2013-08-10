StringObject
===========
The String class is a helper class for when working with strings.

Example usage:

    use Webiny\StdLib\StdObject\StringObject\StringObject;

    $string = new StringObject('Some test string.');

    $string->caseUpper()->trimRight('.')->replace(' test'); // SOME STRING