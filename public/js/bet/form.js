require(
    [
        "vue",
        "jquery",
        "component_bet_entry",
        "component_form_alt_option",
        "component_bet_bet_line",
        "model_bet_bet_line",
        "text!../js/bet/templates/bet-form.html",
        "fraction"
    ],
    function(Vue, $, BetEntry, AltOption, BetLineComponent, BetLine, entryTemplate, Fraction) {
        BetEntryForm = Vue.extend({
            /**
             * The template.
             */
            template: entryTemplate,

            /**
             * Properties which can be passed in
             * via HTML attributes.
             */
            props: [
                'bet',
                'matches'
            ],

            /**
             * Nested components
             */
            components: {
                'alt-option' : AltOption,
                'bet-line' : BetLineComponent
            },

            /**
             * Properties
             *
             * @returns {{bet: string}}
             */
            data:function () {
                return {
                    action : '/bet/process',
                    odds : {}
                }
            },

            /**
             * Part of the vuejs lifecycle, as the name
             * suggests it's called prior to compilation.
             */
            beforeCompile : function() {
                this.bet = JSON.parse(this.bet);
                this.matches = JSON.parse(this.matches);

                if(this.bet.id) {
                    this.action = '/bet/edit/' + this.bet.id
                }

                for(var a in this.bet.lines) {
                    var curLine = this.bet.lines[a];
                    this.odds[a] = new Fraction(curLine.odds);
                }
            },

            /**
             * Watch declarations
             */
            watch : {},

            /**
             * Methods
             */
            methods : {
                /**
                 * Adds new bet line.
                 * @param e
                 */
                addLine : function(e) {
                    e.preventDefault();

                    this.bet.lines.push(BetLine.create());
                },

                /**
                 * Callback function passed to nested
                 * lines. Updates odds for the line.
                 *
                 * @param line
                 * @param odds
                 */
                oddsUpdated : function(line, odds) {
                    if(isNaN(odds) || odds == '') {
                        this.odds[line] = null;
                        //todo: show error
                    } else {
                        this.odds[line] = new Fraction(odds);
                    }

                    this.calculateReturns();
                },


                /**
                 *
                 */
                calculateReturns : function() {
                    var stake = this.bet.amount;

                    for(var a in this.odds) {
                        if(!this.odds[a]) {
                            continue;
                        }

                        stake *= this.odds[a].valueOf();
                    }

                    this.bet.return = new Fraction(stake)
                        .round(2);
                }
            }
        })

        Vue.component('bet-entry-form', BetEntryForm);

        new Vue({el: '#bet-entry-form'})
    }
);