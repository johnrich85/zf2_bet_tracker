require(
    [
        "vue",
        "fraction",
        "bootstrap"
    ],
    function(Vue, Fraction) {

        var BetAmount = Vue.extend({
            template: '<div class="form-group" id="bet-value"><input name="amount" type="text" placeholder="Bet amount" class="form-control" v-model="value"> <input class="odds-field btn btn-small btn-default" name="odds" type="text" v-model="odds" v-on:focus="handleFocus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Example: 5/2 (pause for a second to automatically add the /)"></div>',

            props: [
                'value',
                'odds'
            ],

            methods: {
                notify: function () {
                    if (this.msg.trim()) {
                        this.$dispatch('child-msg', this.msg)
                        this.msg = ''
                    }
                }
            },

            ready: function() {
                $('.odds-field').tooltip();
            },

            methods: {
                'handleFocus' : function() {
                    if(this.odds == 'Enter odds') {
                        this.odds = '';
                    }
                }
            }
        })

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
                    var patt = new RegExp("[0-9]{1,3}\/[0-9]{1,3}");

                    var isFraction = patt.test(this.odds);

                    if(isFraction == false) {
                        $('.odds-field').addClass('has-error');
                        $('.odds-field').tooltip('show');
                        return;
                    }

                    $('.odds-field').removeClass('has-error');

                    var fraction = new Fraction(this.odds);

                    var multiplier = fraction.valueOf() + 1;

                    this.winnings = this.amount * multiplier;
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