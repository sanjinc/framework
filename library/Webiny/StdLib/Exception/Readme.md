Webiny Exception
================

Throw an exception by creating an instance:

    throw new CustomException('You have and error in class %s')->param(['Pero'])->code(113);

Throw an exception over ExceptionTrait:

    throw $this->exception('CustomException')->param(['Pero'])->code(113);

Throw an exception using only exception code:

    throw $this->exception(113)->param('Pero');

