Registry Component
==================
The `Registry` component is used for storing some common data, that you later want to get on some other part of you
application. The data is stored in a from of class attributes.

You can always access the `Registry` from within anywhere inside your application by getting its instance.

Here is how you use it:

```php
    // get the registry
    $registry = \Webiny\Component\Registry\Registry::getInstance();

    // store some data
    $registry->myKey->subKey = 'Value';

    // read the data
    $myData = $registry->myKey->subKey;
```

There is one thing you should be take into account when checking if a key in `Registry` exists. You have to do it by
using the `exists` methods on the `Registry` object. The reason behind this is that you can assign object into the `Registry`
on multiple depths without the need to define the parents (e.g. `$registry->depth1->depth2->depth3 = 'value';`).

```php
     // get the registry
     $registry = \Webiny\Component\Registry\Registry::getInstance();

     // check if a key exists
     if($registry->exists('myKey')){
        // the key exists
     }

     // check for a multi depth key
      if($registry->exists(['key', 'subKey'])){
         // the sub key exists
         // $registry->key->subKey;
      }
```

So, not much to it:
1. Get the instance:
2. Store the data
3. Read the data
