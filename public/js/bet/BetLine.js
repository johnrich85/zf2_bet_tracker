define(
    [
        "vue",
        "jquery",
        "component_form_alt_option",
        "text!../js/bet/templates/bet-line.html",
        "fraction"
    ],
    function(Vue, $, AltOption, template, Fraction) {
        var BetLine = Vue.extend({
            /**
             * The template.
             */
            template: template,

            /**
             * Properties which can be passed in
             * via HTML attributes.
             */
            props: [
                'betLine',
                'index',
                'matches',
                'onOdds'
            ],

            /**
             * Nested components
             */
            components: {
                'alt-option' : AltOption
            },

            /**
             * Properties
             *
             * @returns {{bet: string}}
             */
            data : function () {
                return {
                    targetEle : 'match_name_' + this.index,
                    odds_as_fraction : null
                };
            },

            /**
             * Called prior to component being
             * compiled.
             */
            beforeCompile : function() {
                this.targetEle = this.getTargetEle();

                this.updateFraction();
            },

            /**
             * Watch declarations
             */
            watch : {
                'betLine.odds' : function() {
                    this.onOdds(this.index, this.betLine.odds);

                    this.updateFraction();
                }
            },

            /**
             * Methods
             */
            methods : {
                /**
                 * Returns name of target element.
                 *
                 * @returns {*|void|string|XML}
                 */
                getTargetEle : function() {
                    return this.targetEle
                        .replace('<index>', this.index);
                },

                /**
                 * Populates match fields.
                 *
                 * @param match
                 */
                populateMatch : function (match) {
                    this.betLine.match.id = Number(match.key);
                },

                /**
                 * Shows odds as fraction.
                 */
                updateFraction : function() {
                    if(this.betLine.odds) {
                        this.odds_as_fraction = new Fraction(this.betLine.odds)
                            .toFraction();
                    } else {
                        this.odds_as_fraction = '';
                    }
                }
            }
        })

        Vue.component('bet-line', BetLine);

        return BetLine;
    }
);