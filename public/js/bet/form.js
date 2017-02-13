require(
    [
        "vue",
        "jquery",
        "component_bet_entry",
        "component_form_alt_option",
        "component_bet_bet_line",
        "model_bet_bet_line",
        "text!../js/bet/templates/bet-form.html"
    ],
    function(Vue, $, BetEntry, AltOption, BetLineComponent, BetLine, entryTemplate) {
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

            },

            /**
             * Called when loaded.
             */
            ready : function() {
                this.bet = JSON.parse(this.bet);
            },

            /**
             * Part of the vuejs lifecycle, as the name
             * suggests it's called prior to compilation.
             */
            beforeCompile : function() {
                this.matches = JSON.parse(this.matches);
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
                }
            }
        })

        Vue.component('bet-entry-form', BetEntryForm);

        new Vue({el: '#bet-entry-form'})
    }
);