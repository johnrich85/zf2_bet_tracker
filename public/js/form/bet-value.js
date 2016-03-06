(function ($) {
    $.extend({
        betValue: new
            function () {

                var lastKeyUpAt = 0;

                /**
                 * Adds instructional tooltip to
                 * text field.
                 *
                 * @param ele
                 */
                function addTooltip(ele, msg) {
                    ele.attr('data-toggle', "tooltip");
                    ele.attr('data-placement', "top");
                    ele.attr('title', msg);

                    ele.tooltip();
                }

                /**
                 * Returns odds field HTML.
                 *
                 * @returns {string}
                 */
                function getOddsHtml() {
                    var html = '<input';
                    html += ' class="odds-field btn btn-small btn-default"';
                    html += ' name="odds"';
                    html += ' type="text"';
                    html += ' value="Enter odds"';
                    html += '>';

                    return html;
                }

                /**
                 * Adds field to hold odds.
                 *
                 * @param ele
                 */
                function addOddsField(ele) {
                    var input = $(getOddsHtml());

                    ele.parent()
                        .append(input);

                    var field = $('.odds-field');

                    field.focus(function() {
                        $(this).val('');
                    })

                    var message = 'Example: 5/2 (pause for a second to automatically add the /)';

                    addTooltip(field, message);

                    field.keyup(oddsKeyUp);
                    field.keydown(oddsKeyDown);
                    field.focusout(oddsBlur);
                }

                /**
                 * Handles bet entry.
                 */
                function addBetEntryHandler(ele) {
                    ele.keydown(function(e){
                        var code = e.keyCode || e.which;

                        if(code == 192) {
                            e.preventDefault();
                            $('.odds-field').focus();
                        }
                    })
                }

                /**
                 * Handles keyup even on odds field.
                 */
                function oddsKeyUp() {
                    lastKeyUpAt = new Date();
                }

                /**
                 * Handles keydown event on odds field.
                 */
                function oddsKeyDown() {
                    var field = $(this);
                    var value = field.val();
                    var now = new Date();

                    if(value.length ==0 || value.length > 3 || lastKeyUpAt == 0)
                        return;

                    var diff = parseInt((now - lastKeyUpAt));

                    if(diff > 400) {
                        field.val(value + " / ");
                    }
                }

                /**
                 * Handles blur event on odds field.
                 */
                function oddsBlur() {
                    lastKeyUpAt = 0;
                }

                /**
                 * Creates alternativeOption instance.
                 *
                 * @param settings
                 */
                this.construct = function (settings) {
                    var ele = $(this)
                        .find('input[type=text]');

                    if(!ele.length) {
                        var message = "Unable to find 'bet amount' text field.";
                        console.log(message);
                        return
                    }

                    var msg = "Enter bet amount then click '@' to enter odds";

                    addTooltip(ele, msg);
                    addOddsField(ele);
                    addBetEntryHandler(ele);
                };
            }
    });

    // extend plugin scope
    $.fn.extend({
        betValue: $.betValue.construct
    });

})(jQuery);

//todo: move to separate file.
$(function($) {
    $("#bet-value").betValue({});
});