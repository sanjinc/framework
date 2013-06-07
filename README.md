framework
=========

Webiny framework repository. Private for now.

Testing.

Ovo je promjena.

Some notes on coding:
=========
- before writing any code, make sure you have read Webiny Coding Standard (documentation repo:for-developers\coding-standard)
- each package should have its own exception handler
- prefer the usage of 'use' keyword instead of writing the full class name with namespace
- config class?
- *ako funkcija prima StdObject onda i vraća StdObject, i obrnuto ako prima string


COMPONENT NOTES:
================
The "Http\Response" object must utilize HTTP Cache (http://symfony.com/doc/current/book/http_cache.html)

Modules:
ServiceProvider => http://symfony.com/doc/master/book/service_container.html
                => http://symfony.com/doc/master/cookbook/service_container/scopes.html
QueryBuilder => https://github.com/laravel/laravel/tree/3.0/laravel/database
Mail => SwiftMail