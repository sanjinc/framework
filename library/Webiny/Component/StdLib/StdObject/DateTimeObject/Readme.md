DateTimeObject
===========
The DateTimeObject class is a helper class for when working either with date or time.

Example usage:

    use Webiny\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;

    $dt = new DateTimeObject('3 months ago');
    echo $dt; // 2013-02-12 17:00:36

    $dt->add('10 days')->sub('5 hours'); // 2013-02-22 12:00:36