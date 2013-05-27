- TODO
- dokumentiraj config i bridgeve na svim komponentama

Crypt Component
===============
The `Crypt` component provides methods for generating random numbers and strings, also, password hashing and password
hash verification and methods for encryption and decryption of strings.

This library utilizes PHP-CryptLib library by Anthony Ferrara.
https://github.com/ircmaxell/PHP-CryptLib

## Generate random integers

To generate a random integer you just have to pass the range to the `Crypt` instance:

```php
    $crypt = new \Webiny\Component\Crypt\Crypt();
    $randomInt = $crypt->generateRandomInt(10, 20); // e.g. 15
```

## Generate random strings

When you want to generate random string, you have several options. You can call the general `generateRandomString` method,
or you can call `generateUserReadableString` method to get a more user-readable string that doesn't contain any special
characters. There is also a method called `generateHardReadableString` that, among letters and numbers, uses special
characters to make the string more "harder".
Here are a few examples:

```php
    $crypt = new \Webiny\Component\Crypt\Crypt();

    // generate a string from a defined set of characters
    $randomString = $crypt->generateRandomString(5, $chars = 'abc'); // e.g. cabcc

    // generate a string that contains only letters (lower & upper case and numbers)
    $randomString = $crypt->generateUserReadableString(5); // A12uL

    // generate a string that can contain special characters
    $randomString = $crypt->generateHardReadableString(5); &"!3g
```

## Password hashing and validation

A preferred way of storing users passwords in a database is by hashing/encrypting it first. You can use common hashing
algorithms like `md5` or `sha1`, but a more secure way is using encryption algorithms like Blowfish.
This component comes with a support for encrypting and validating passwords using such a method.

```php
    $crypt = new \Webiny\Component\Crypt\Crypt();

    // hash password
    $passwordHash = $crypt->createPasswordHash('login123'); // $2y$08$GgGha6bh53ofEPnBawShwO5FA3Q8ImvPXjJzh662/OAWkjeejAJKa

    // (on login page) verify the hash with the correct password
    $passwordsMatch = $crypt->verifyPasswordHash('login123', $passwordHash); // true or false
```

## Encrypting and decrypting strings

The last feature provided by this component is encryption and decryption of strings. This process uses a secret key and
a initialization vector (http://en.wikipedia.org/wiki/Initialization_vector). Both parameters must be exactly the same
for the decryption process as they were for the encryption process, or else the string cannot be decrypted to its
original form.

```php
    $crypt = new \Webiny\Component\Crypt\Crypt();

    // encrypt it
    $encrypted = $crypt->encrypt('some data', 'abcdefgh12345678'); // (some string that cannot be read)

    // decrypt it
    $decrypted = $crypt->decrypt($result, 'abcdefgh12345678'); // "some data"
```

# Crypt config

## About

`Crypt` config has the following options:

### "password_algo"

The algorithm used for hashing passwords. Supported algorithms depend on the defined `bridge` library.
The default library, PHP-CryptLib, supports:
    - **BCrypt** - (*default*)
    - **PBKDF1**
    - **PBKDF2**
    - **SHA256** - (crypt()'s implementation)
    - **SHA512** - (crypt()'s implementation)
    - **Schneier** (a PBKDF derivative)

### "cipher_mode"

This is the mode that will be used for encrypting and decrypting strings.
Following modes are supported by the default library:
    - **CBC** - Encryption (Cipher Block Chaining) - (*default*)
    - **CCM** - Encryption and Authentication (Counter Cipher Block Chaining)
    - **CFB** - Encryption (Cipher FeedBack)
    - **CTR** - Encryption (Counter)
    - **ECB** - Encryption (Electronic CodeBook)
    - **NOFB** - Encryption (Output FeedBack - Variable Block Size)


### "cipher_block"

`cipher_block` is the portable block cipher used, in combination with `cipher_mode` for the encrypt/decrypt method.
The following options are available:
    - **aes-128**
    - **aes-192**
    - **aes-256**
    - **rijndael-128** - (*default*)
    - **rijndael-160**
    - **rijndael-192**
    - **rijndael-224**
    - **rijndael-256**
    - **des**
    - **tripledes**

### "cipher_initialization_vector"

This option holds the default value for the initialization vector used in `encrypt` and `decrypt` methods.
The default value is `_FOO_VECTOR`.

## Default config

This is how the defaul config looks like:

```yaml
components:
  crypt:
      password_algo: 'Blowfish'
      cipher_mode: 'CCM'
      cipher_block: 'rijndael-128'
      cipher_initialization_vector: '_FOO_VECTOR'
```