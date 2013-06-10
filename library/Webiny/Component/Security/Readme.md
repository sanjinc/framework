Security Component
==================
*This component is still in development*

##Notes
* The process goes like this:
1. A user accesses a protected resource
2. The application redirects the user to the login form
3. The user submits its credentials (e.g. username/password);
4. The firewall authenticates the user;
5. The authenticated user re-tries the original request.

# TODO
on Token\TokenAbstract::encryptUserData -> use the crypt over the service manager so that we don't have to hardcode the data