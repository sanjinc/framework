OAuth2 Wrappers
==============

This component provides wrappers for several OAuth2 systems like Facebook, LinkedIn and Google.
After you have gained OAuth2 access token, you can use this wrapper to communicate with the the desired service.

## Supported OAuth2 servers

Current supported OAuth2 servers are:

* Facebook
* Google
* LinkedIn

## Configuring the component

To use the component, you first need to configure it.
The configuration is done by defining the following params:

- **server** - name of the server defined in `components.oauth2.servers`
- **client_id** - OAuth2 client id
- **client_secret** - OAuth2 client secret
- **scope** - scope parameter based on the selected OAuth2 server
- **redirect_uri** - location where the user will be redirected by the OAuth2 server once he is authorized

### Example configuration:

```yaml
oauth2:
    webiny_test:
        server: facebook
        client_id: 102119699173473
        client_secret: 3a617d1a94f5e835a6e1afd4b6f8a05b
        scope: email
        redirect_uri: /Test/Security/oauth_fbsubmit.php
    google_test:
        server: google
        client_id: 280148463887.apps.googleusercontent.com
        client_secret: X7TkmIJTAFm46yPOKBlg3-dT
        scope: openid%20profile%20email
        redirect_uri: /Test/Security/oauth_gsubmit.php
```

## Usage

This component depends on users access token, without it no API call to the OAuth2 server can be made.
To get the access token, please read the implementation guide for a specific server you wish to use.

Example:
```php
// load instance of `google_test` configuration
$instance = OAuth2Loader::getInstance('google_test');

// set access token
$instance->setAccessToken('...');

// do API requests to get user details
$userProfile = $instance->request()->getUserDetails();

// do an API request to a specific API method
$result = $instance->request()->rawRequest($url, $params);
```

## Registering additional servers

First create a class that extends `\Webiny\Component\OAuth2\ServerAbstract` and then implement the abstract methods.
All of the abstract methods are described inside `ServerAbstract` class, and additionally you should also check out how
implementations of current servers looks like. They are located in `\Webiny\Component\OAuth2\Server` folder.

```php
class Instagram extends \Webiny\Component\OAuth2\ServerAbstract
{
    public function getAuthorizeUrl(){
        // TODO: Implement _getUserDetailsTargetData() method.
    }

    public function getAccessTokenUrl(){
        // TODO: Implement _getUserDetailsTargetData() method.
    }

	protected function _getUserDetailsTargetData() {
		// TODO: Implement _getUserDetailsTargetData() method.
	}

	protected function _processUserDetails($result) {
		// TODO: Implement _processUserDetails() method.
	}

	public function processAuthResponse($response) {
		// TODO: Implement processAuthResponse() method.
	}
}
```

Once you have implemented your logic for the abstract methods, it's time to register the class with the OAuth2 component.
In order to do so, inside your config file, under `components.oauth2.server` section add your class and the name of the
server and the location of your class.

```yaml
components:
    oauth2:
        servers:
            instagram: \MyLib\OAuth2\Server\Instagram
```

And you're done!
To use it, just configure it the same way as the built in classes.