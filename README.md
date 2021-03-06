zf2_bet_tracker
===============

Bet tracker made using ZF2


##### Misc Task list

1. ~~Match scraping~~
2. ~~Resolve casters/parsers based on page.~~
3. Events defaulting when source not found - currently no way to rectify this (Need to scrape events, also Need to record URL against matches & resolve during event scrape.)
4. GosuLoLCaster to extend base MatchCaster
5. When selecting a match from db, link bet to db.
6. Multiple matches per bet.
7. ~~Finish Odds field (need to handle delete key, shoudl reset timer)~~
8. ~~Get vue.js working.~~
9. Rewrite custom jquery plugins as vue.js models.
10. remove bootstrap css & replace with bower_component counterpart
11. Partials for bet/index reports.


####  Priority 1: Improved betting

##### Multiple lines per bet.

1. ~~Update form so that multiple lines can be rendered.~~
2. ~~Add hydrators~~
3. ~~On add bet, bet line form(or model) is not populate~~
4. ~~Update controller so that onPost form is repopulated~~
5. ~~Update service so that it handles new line data.~~
6. New BetLine component in vue - pump in bet lines and generate fieldset (need to store a global bet return based on odds of all bet lines).
6. Bet line match population (see hydrator)
7. UI button to add new line.

0. ~~Move source page generation out of index controller - separate action.~~
1. ~~Scrape match sources, update winner. (step 1, create source page for each match)~~
2. ~~Relationship between match & SourcePage.~~
3. ~~Then create controller action to scrape all completed matches.~~
5. ~~When selecting a match (on bet add/edit form), link that match to the bet.~~
6. When a bet result is scraped, mark associated bets as winners/losers.
7. Bet status extension (win/lose/pending)
8. List bets by day/week/month - group w/l for period, graphs for bets for sport or weekly etc

####  Priority 2: Match history

1. Add new section to interface - Matches (filterable by sport).
2. Add new team section to interface. Show recent match history.
3. Two new pages for matches section - Upcoming match (recent patches, win %, key facts) / Match results.

####  Priority 3: Improved team model

1. Extend team model - current rating, notes, key facts, key players etc. Allow allow user input (key facts with tags: weight, plus/negative, expiry_date)
2. New Player model, linked to team.# - player recent stats, current rating, injured/suspended etc.


### Improved betting point 4 sub checklist

1. Create new vue model for Modals.
2. Create new vue model for Select (with/without optrgoups).
3. ~~Create new 'alternative-option' vue model (adds button to ele, adds modal containing select, binds select value to input).~~
4. ~~Create new bet line vue model, create bet lines vue model. Create x amount of bet lines based on lines array.~~
5. ~~Add new button to UI that allows more lines to be added.~~

##### Next up

1. Logging
2. ~~Template engine.~~
2. Template engine - get layout working properly.
3. ~~XSS escaping.~~
4. ~~CSRF for forms.~~
5. Users, Authentication & ACL.
6. ~~Frontend modules/dependency manager~~
7. ~~Make modules self contained (add css/js etc to module, will need a 'publish' method to push to publci folder).~~


##### Bugs

1. ~~Match date being calculated wrong, results in match being scraped multiple times.~~



##### Installation

1. Clone repo.
2. Install dependencies via composer (composer.phar install)
3. Install frontend dependencies (bower install)
4. Build schema (vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:update

##### Commands
1. *Scrape completed matches* - php public/index.php scrape matches
