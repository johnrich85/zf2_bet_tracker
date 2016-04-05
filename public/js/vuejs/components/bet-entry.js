require(
    [
        "vue",
        "fraction",
        "bootstrap"
    ],
    function(Vue, Fraction) {
        var BetAmount = Vue.extend({
            template: '<div class="form-group" id="bet-value"><input name="amount" type="text" placeholder="Bet amount" class="form-control" v-model="value"> <input class="odds-field btn btn-small btn-default" name="odds" type="text" v-model="odds" v-on:focus="handleFocus" v-on:keyup="handleKeyup" data-toggle="tooltip" data-placement="top" title="" data-original-title="Example: 5/2 (pause for a second to automatically add the /)"></div>',

            props: [
                'value',
                'odds'
            ],


            ready: function() {
                $('.odds-field').tooltip();
            },

            methods: {
                /**
                 * Removes default text
                 */
                handleFocus : function() {
                    if(this.odds == 'Enter odds') {
                        this.odds = '';
                    }
                },

                /**
                 * Adds forward slash to odds after
                 * 500ms pause.
                 */
                handleKeyup : function(e) {
                    var self = this;

                    var now = new Date()
                        .getTime();

                    if(e.keyCode == 8 || now - this.last < 500) {
                        return;
                    }

                    this.last = now;

                    setTimeout(function () {
                        self.appendForwardSlash();
                    }, 200);
                },

                /**
                 * Adds forward slash to odds string
                 */
                appendForwardSlash: function() {
                    var oddsLength = this.odds.length;
                    var hasSlash = this.odds.search('[\/]') == 1;

                    if(hasSlash || oddsLength == 0 || oddsLength > 3) {
                        return;
                    }

                    this.odds = this.odds + "/";
                }
            }
        });

        var BetReturn = Vue.extend({
            template: '<div class="form-group"> <input name="return" type="text" placeholder="Total return" class="form-control" v-model="value"> </div>',
            props: [
                'value'
            ]
        })

        var BetEntry = Vue.extend({
            /**
             * The template.
             */
            template: '<bet-amount :value.sync="amount" :odds.sync="odds"></bet-amount> <bet-return :value.sync="winnings"></bet-return>',

            /**
             * Properties which can be passed in
             * via HTML attributes.
             */
            props: [
                'amount',
                'winnings'
            ],

            /**
             * Nested components
             */
            components: {
                'bet-amount': BetAmount,
                'bet-return': BetReturn
            },

            /**
             * Properties
             *
             * @returns {{odds: string}}
             */
            data:function () {
                return {
                    odds: 'Enter odds'
                }
            },

            ready : function() {
                if(this.winnings == false || this.amount == false) {
                    return;
                }
                
                this.odds = this.calculateOdds();
            },

            /**
             * Watch declarations
             */
            watch : {
                odds : 'calculateReturns',
                amount: 'calculateReturns'
            },

            /**
             * Methods
             */
            methods : {
                /**
                 * Calculates the return value from the
                 * bet amount & odds.
                 */
                calculateReturns : function() {
                    var pattern = new RegExp("[0-9]{1,3}\/[0-9]{1,3}");

                    var isFraction = pattern.test(this.odds);

                    if(isFraction == false) {
                        $('.odds-field').addClass('has-error');
                        $('.odds-field').tooltip('show');
                        return;
                    }

                    $('.odds-field').removeClass('has-error');

                    var fraction = new Fraction(this.odds);

                    var multiplier = fraction.valueOf() + 1;

                    this.winnings = this.amount * multiplier;
                },

                /**
                 * Calculates odds from winnings and bet
                 * amount.
                 *
                 *
                 *
                 * @returns {string}
                 */
                calculateOdds : function() {
                    var decimal = this.winnings / this.amount - 1;

                    var f = new Fraction(decimal);

                    var fraction = f.toFraction();

                    if(fraction.match(/\//) == null) {
                        fraction += "/1";
                    }

                    return fraction;
                }
            }
        })


        //TODO: move this out of here. Find a better way to declare templates.
        Vue.component('bet-entry', BetEntry);

        new Vue({
            el: '#bet-entry'
        })
    }
);