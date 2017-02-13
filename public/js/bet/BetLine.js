define(
    [
        "vue",
        "jquery",
        "component_form_alt_option",
        "text!../js/bet/templates/bet-line.html"
    ],
    function(Vue, $, AltOption, template) {
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
                'matches'
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
                    targetEle : 'lines[<index>][match]'
                };
            },

            /**
             * Called prior to component being
             * compiled.
             */
            beforeCompile : function() {
                this.targetEle = this.getTargetEle();
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
                 * Returns name of target element.
                 *
                 * @returns {*|void|string|XML}
                 */
                getTargetEle : function() {
                    return this.targetEle
                        .replace('<index>', this.index);
                }
            }
        })

        Vue.component('bet-line', BetLine);

        return BetLine;
    }
);