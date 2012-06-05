## OG Action Importer Example

This is a simple OAuth example provider and importer endpoint. The user first hits `/oauth/authorize` which redirects to `/oauth/success` when they clik OK. That issues a code which is returned to `/oauth/access_token` which issues an access token to be used with `/import`.

If you run it on heroku, it needs the free memcache addon.  You can get it with:

    heroku addons:add memcache:5mb
