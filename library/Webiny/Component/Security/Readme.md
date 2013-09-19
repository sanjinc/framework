Security Component
==================

The security component is a layer that sits on top of your applications and takes care of the authentication and
authorization processes for you.
The component itself needs only to be configured and requires no coding at all.

Before you go into the details, is important that you are familiar with the terms of authorization, authentication and
access control, if you are not, please read the following articles:

- http://www.cyberciti.biz/faq/authentication-vs-authorization/
- http://stackoverflow.com/questions/6556522/authentication-versus-authorization

If you what to know more:

- http://en.wikipedia.org/wiki/Authorization
- http://en.wikipedia.org/wiki/Authentication
- http://en.wikipedia.org/wiki/Access_control

# Usage

The usage of the component is fairly simple:

```php
class MyClass
{
    use SecurityTrait;

    function myMethod(){
        $user = $this->security()->getUser();
    }

    function onlyForAdmin(){
        if(!$this->security()->isGranted('ROLE_ADMIN')){
            die('You are not the Admin');
        }
    }
}

```

# Example configuration

This is an example configuration of the security layer.
The next few topics will describe every part of the configuration.

```yaml
security:
    encoders:
        default:
            driver: '\Webiny\Component\Security\Encoder\Drivers\Crypt'
            params: ['webiny_crypt']
            salt: 'CHANGE THIS SECRET'
    providers:
        memory:
            john: {password: secret, roles: 'ROLE_USER'}
            admin: {password: login123, roles: 'ROLE_SUPERADMIN'}
        oauth2:
            driver: 'Webiny\Component\Security\User\Providers\OAuth2\OAuth2'
    chain_providers:
        default_chain: [oauth2, memory]
    role_hierarchy:
        ROLE_USER: ROLE_EDITOR
        ROLE_ADMIN: ROLE_USER
        ROLE_SUPERADMIN: [ROLE_ADMIN, ROLE_CUSTOM]
    access_control:
        rules:
            - {path: ^/admin, roles: ROLE_ADMIN}
            - {path: ^/Test, roles: [ROLE_ADMIN]}
        decision_strategy: consensus
    firewalls:
        administration:
            realm_name: 'My administration realm'
            url_pattern: '^/Test/'
            anonymous: false
            remember_me: true
            #encoder: default
            security_key: THIS_IS_MY_KEY98
            provider: default_chain
            login:
                login_path: '/Test/Security/login.php'
                failure_path: '/Test/Security/loginfailed.php'
                target_path: 'Test/Test.php'
                providers:
                    form:
                        driver: '\Webiny\Component\Security\Authentication\Providers\Form\Form'
                        submit_path: '/Test/Security/form_loginsubmit.php'
                    http:
                        driver: '\Webiny\Component\Security\Authentication\Providers\Http\Http'
                        submit_path: '/Test/Security/http_loginsubmit.php'
                    facebook:
                        driver: '\Webiny\Component\Security\Authentication\Providers\OAuth2\OAuth2'
                        submit_path: '/Test/Security/oauth_fbsubmit.php'
                        params:
                            server: webiny_test
                            roles: [ROLE_ADMIN]
                    google:
                        driver: '\Webiny\Component\Security\Authentication\Providers\OAuth2\OAuth2'
                        submit_path: '/Test/Security/oauth_gsubmit.php'
                        params:
                            server: google_test
                            roles: [ROLE_ADMIN]
                    linkedin:
                        driver: '\Webiny\Component\Security\Authentication\Providers\OAuth2\OAuth2'
                        submit_path: '/Test/Security/oauth_lisubmit.php'
                        params:
                            server: linkedin_test
                            roles: [ROLE_ADMIN]
            logout:
                path: '/Test/Security/logout.php'
                target: '/Test/Security/logoutsuccess.php'
```

# Components

The security layer is actually a set of several components that work and communicate together. Each of these components
needs to be configured.

The next sections will go through the components and explain what they do.

## Encoders (`security.encoders`)

Encoders are services that are responsible for two things, creating a password hash from the provided string and
verifying if the submitted password matches the given hash.

The encoder comes with default Crypt driver that uses the built in `Crypt` component for encoding and verifying password.
The driver requires that you have at least one crypt service defined under `services`. Just provide the name of the crypt
service under `params` and your encoder is ready.

Example encoder configuration:

```yaml
# encoder configuration
security:
    encoders:
        default:
            driver: \Webiny\Component\Security\Encoder\Drivers\Crypt
            params: [webiny_crypt]
            salt: CHANGE THIS SECRET
```

To create a custom encoder driver, you need to create a class that implements
`\Webiny\Component\Security\Encoder\EncoderDriverInterface`.

## (User) Providers (`security.providers`)

Providers are like a databases from whom the `Security` component loads users. Each provider consists of 2 parts, a user
provider, and the user component itself. The provider part is responsible for loading users based on submitted login
credentials, while the user object is responsible for verifying the submitted credentials against the load object from
the provider.

There are two built-in user providers, the `Memory` provider and the `OAuth2` provider.

### Memory provider

The `Memory` provider gives you the option to define users directly inside your configuration file, and it looks like this:

```yaml
security:
    providers:
        my_test_users:
            john: {password: God, roles: ROLE_USER}
            admin: {password: Love, roles: ROLE_SUPERADMIN}
        my_other_users:
            jack: {password: Secret, roles: ROLE_ADMIN}
```

### OAuth2 provider

The OAuth2 provider depends on the `OAuth2` component and it must be wrapped together with the authentication provider,
described in the later topics.
To configure the OAuth2 user provider you just need to set the path to the built-in driver:

```yaml
security:
    providers:
        oauth2:
            driver: Webiny\Component\Security\User\Providers\OAuth2\OAuth2
```

### Custom user providers

To implement a custom user provider you need to create a class that implements
`\Webiny\Component\Security\User\UserProviderInterface`. And you need to create a user class that extends
`\Webiny\Component\Security\User\UserAbstract`. And that's it, all other details are described inside the interface and
the abstract class.

## Chain providers (`security.chain_providers`)

Chain providers are defined sets of user providers joined together. This is useful when you have users stored in several
"databases" and you need to check all of them to see which one will actually find the user. So that you don't need to
create a user provider that checks several user providers for you, we have created this chain provider that handles that.

Example definition of chain providers:

```yaml
security:
    chain_providers:
        default_chain: [oauth2, my_test_users, my_other_users]
```

## Role hierarchy (`security.role_hierarchy`)

This component is mostly self-explanatory, it defines the list of available roles and their hierarchy.

Here is an example:

```yaml
security:
    role_hierarchy:
        ROLE_USER: ROLE_EDITOR
        ROLE_ADMIN: ROLE_USER
        ROLE_SUPERADMIN: [ROLE_ADMIN, ROLE_CUSTOM]
```

## Access control (`security.access_control`)

Access control is the central part that handles the authorization.
Inside access control you define a set of rules where each rule consist of a path and a list of roles that are required
for accessing that path.

```yaml
security:
    access_control:
        rules:
            - {path: ^/admin, roles: ROLE_ADMIN}
            - {path: ^/Test, roles: [ROLE_EDITOR,ROLE_ADMIN]}
        decision_strategy: consensus
```

### Voters

Access control also has internal mechanism called `Voters`. These are like a jury that can either vote

- `ACCESS ALLOWED`
- `ACCESS_DENIED`
- `ACCESS_ABSTAINED`

There are two built in voters, the `AuthenticationVoter` that votes based on if user is authenticated or not, and there
is `RoleVoter` that votes based on if user has the necessary role to access the current area.

The logic behind voters is created so you can extend it and add your own voters. For example you can create a voter
that either allows or denies access based on users IP address, like a black-list filter.

To create a custom voter you need to create a class that implement `\Webiny\Component\Security\Authorization\Voters\VoterInterface`.
After that, you need to create a service and tag it with `security.voter`.

```yaml
services:
    security:
        voters:
            my_voter:
                class: \MyCustomLib\MyCustomVoter
                tags: [security.voter]
```

### Decision strategy

Decision strategy is the property that defines how the system will make its ruling, either to allow or deny access,
based on the votes for the voters.

There are three different strategies that can be applied:
- *unanimous* - all voters must vote ACCESS_ALLOWED to allow access
- *affirmative* - only one ACCESS_ALLOWED vote is enough to allow access
- *consensus* - majority wins (tie denies access)

## Firewall (`security.firewalls`)

Firewall is the central component that controls the authentication layer.
Firewall is an area inside which we track the user and how he is authenticated. You can have firewalls that allow
anonymous access, meaning the user doesn't need to be signed-in in order to access it.
Basically, if you need to get the current user, he needs to be inside the firewall, authenticated or not, doesn't matter.


You can have multiple sets of firewall. Each firewall consists of following parameters:
- realm_name
    - user readable name of the current realm
- url_pattern
    - all pages that match this pattern will trigger the firewall
    - if the request matches multiple patterns, the first matched pattern will be selected, and others will be discarded
- anonymous
    - is anonymous access allowed behind this firewall or not
- remember_me
    - do you want to remember the users credentials for a period of time or just the current session
- encoder
    - if your passwords are hashed (and they should be), place the name of the encoder that you defined in `security.encoders`
    - if your passwords are not hashed, meaning they are in raw format, then don't define the encoder
- security_key
    - this is the security key that will be used to encrypt the authentication token
    - the key must have a length of 16, 32 or 64 characters
    - make sure you have a phrase that is hard/impossible to guess
- provider
    - this is the user provider that the firewall will use to ask for user account
    - you can put here a user provider `security.providers`, or a chain provider `security.chain_providers`
- login
    - login is a param that consists of several attributes
    - `login_path` is the path to the login page where the user can start the authentication process
        - to this path user is redirected each time he is trying to access an area for which he doesn't have the required access level
    - `failure_path` to this path user is redirected each time the authentication process failed, e.g. wrong username and password
    - `target_path` is the path where the user will be redirected after successful authentication
    - `providers` hold a list of authentication providers (see section Authentication providers)
- logout
    - the logout param is consisted of two attributes, `path` and `target`
    - `path` defines the path where the logout process will be triggered
    - `target` defines the path where the user will be redirected once the logout process is done

### Authentication providers

Authentication providers are ways of authenticating uses.

This is an example configuration for an auth provider:

```yaml
http:
    driver: '\Webiny\Component\Security\Authentication\Providers\Http\Http'
    submit_path: '/Test/Security/http_loginsubmit.php'
```

The configuration must have two parameters, the `driver` param that defines which class to use to process the authentication,
and the `submit_path` that is used to detect which driver to load based on the current url. It's really important that
each auth provider has its own submit path.

Additional parameters might be required for some other auth providers.

There are also three built-in auth providers:

#### Http auth provider

This is the basic Http authentication.
Driver: `\Webiny\Component\Security\Authentication\Providers\Http\Http`

#### Form auth provider

Use this provider when you have a HTML login form for authenticating your users.
Driver: `\Webiny\Component\Security\Authentication\Providers\Form\Form`

Make sure that the `action` parameter in your HTML form points to the `submit_path`.

#### OAuth2 auth provider

This provider uses the OAuth2 protocol and the `OAuth2` component. The supported OAuth2 servers are defined the by
the `OAuth2` component.
Driver: `\Webiny\Component\Security\Authentication\Providers\OAuth2\OAuth2`

This provider requires a bit more configuration, so here is an example:

```yaml
google_auth:
    driver: '\Webiny\Component\Security\Authentication\Providers\OAuth2\OAuth2'
    submit_path: '/Test/Security/oauth_gsubmit.php'
    params:
        server: google_test
        roles: [ROLE_ADMIN]
```

Notice the two attributes inside the params section, the `server` attribute points to the defined OAuth2 configuration,
while `roles`param define which roles will be assigned to the users that are authenticated over this provider.

## Requirements

In order to the `Security` class you need to have the system crypt service enabled.
See the section `Crypt as a service` inside the readme file in the Crypt component folder.

## Events

The component fires several events that you can subscribe to:

- `wf.security.login_invalid` fired when user submits invalid login credentials
- `wf.security.login_valid` fired when user submits valid login credentials
- `wf.security.role_invalid` fired when authenticated user tries to enter an area that requires a higher role than he currently has

All events pass an instance of `\Webiny\Component\Security\SecurityEvent`.

## Best practices and common mistakes

### Tip #1

Make sure that places, where you need to check or get the current user, are behind a firewall.

### Tip #2

Make sure that the `login_path` is, either under a firewall that allows anonymous access, or outside the firewall.
All other paths **must be** inside the firewall.
