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
Na token storage-u vidi da encKey provučemo negdje kroz konfiguraciju
Ubaci evente unutar security-a
Provuci čitanje votera preko taga iz service manager-a
Dodaj još par oauth2 login sistema.
Vidi što je sa twitterom.
SecurityTrait
Dokumentacija!?!?


STAO:
-> problem je kod logout-a sta se ne moze trigerati logoutCallback jer se ne moze loadati auth provider