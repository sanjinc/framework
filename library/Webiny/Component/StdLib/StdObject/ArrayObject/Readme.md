ArrayObject
===========
The ArrayObject class is a helper class for when working with arrays.
It's fully an objective approach to manipulating and validating arrays.

Example usage:

    use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;

    $array = new ArrayObject(['one', 'two', 'three']);

    $array->first(); // StringObject 'one'

    $array->append('four')->prepend('zero'); // ['zero', 'one', 'two', 'three', 'four']