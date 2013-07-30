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
    -> riješeno
Ubaci evente unutar security-a
    -> eventi dodani
Provuci čitanje votera preko taga iz service manager-a
    -> dodano i testirano
Dodaj još par oauth2 login sistema.
    -> dodano google, fb i linkedin
Vidi što je sa twitterom.
    -> twitter ne ide dok ne implementira oauth2
SecurityTrait
Dokumentacija!?!?
Testiraj votere - sva 3 tipa odlučivanja
    -> testirano


DOCUMENTATION NOTES:
- stavi u dokumentaciju requirements dio gdje će stajati svi zahtjevi security module-a
    - pod requirements stavi da mora postojati webiny_crypt instanca definirana u configu

Eventi:
wf.security.login_invalid   -> invalid login credentials submitted
wf.security.login_valid     -> valid login credentials submitted (UserAbstract)
wf.security.role_invalid    -> user is authenticated, but doesn't have the right role to access the current area (UserAbstract)
