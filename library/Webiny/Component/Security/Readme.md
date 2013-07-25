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
Voters:
http://symfony.com/doc/current/cookbook/security/voters.html
http://kriswallsmith.net/post/15994931191/symfony2-security-voters

STAO:
1. FORM login sada radi submit i logira korisnika..sada napravi form logout te zatim JAKO dobro testiraj access rules
4. oauth login?!?!