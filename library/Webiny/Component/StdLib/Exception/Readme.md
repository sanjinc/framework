Webiny Exception
================

A library of some common exceptions. You can extend it to create custom exceptions.

To use the common exceptions:

    use Webiny\Component\StdLib\Exception\Exception;

    // throw an exception with a custom message
    throw new Exception('My exception message');

    // or some built message
    throw new Exception(Exception::MSG_INVALID_ARG, ['$length', 'integer']);
    // -> this outputs "Invalid argument provided. $length must be type of integer."


To extend this library and throw custom exceptions:

    class MyCustomExceptionClass extends Webiny\Component\StdLib\Exception\ExceptionAbstract
    {

    }


To define custom exception codes and messages:

    class MyCustomExceptionClass extends Webiny\Component\StdLib\Exception\ExceptionAbstract
    {
        const MSG_INVALID_URL = 101;

    	static protected $_messages = [
    		101 => 'Unable to parse "%s" as a valid url.'
    	];
    }

    // usage example
    throw new MyCustomExceptionClass(MyCustomExceptionClass::MSG_INVALID_URL, ['Some string']);


Important rules when defining custom exceptions:
- Always extend the Webiny\Component\StdLib\Exception\ExceptionAbstract class
- Internal constants that contain the exception code must start from 101. Lower numbers are reserved for core exception messages.
- You must define static protected $_messages array. $_messages keys must match the numbers in the constants.