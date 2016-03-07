zf2_bet_tracker
===============

Bet tracker made using ZF2


##### Current Task list

1. ~~Match scraping~~
2. ~~Resolve casters/parsers based on page.~~
3. Events defaulting when source not found - currently no way to rectify this (Need to scrape events, also Need to record URL against matches & resolve during event scrape.)
4. GosuLoLCaster to extend base MatchCaster
5. When selecting a match from db, link bet to db.
6. Multiple matches per bet.
7. Finish Odds field (need to handle delete key, shoudl reset timer)
8. Get vue.js working.
9. Rewrite custom jquery plugins as vue.js models.
10. remove bootstrap css & replace with bower_component counterpart

##### Next up

1. Logging
2. Template engine.
3. XSS escaping.
4. ~~CSRF for forms.~~
5. Users, Authentication & ACL.
6. ~~Frontend modules/dependency manager ~~


##### Bugs

1. ~~Match date being calculated wrong, results in match being scraped multiple times.~~




##### Installation

1. Clone repo.
2. Install dependencies via composer (composer.phar install)
3. Install frontend dependencies (bower install)
4. Build schema (vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:update
