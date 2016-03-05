(function ($) {
    $.extend({
        betValue: new
            function () {

                /**
                 * Adds instructional tooltip to
                 * text field.
                 *
                 * @param ele
                 */
                function addTooltip(ele) {
                    ele.attr('data-toggle', "tooltip");
                    ele.attr('data-placement', "top");
                    ele.attr('title', "Enter bet amount then click '@' to enter odds");

                    ele.tooltip();
                }

                /**
                 * Adds field to hold odds.
                 *
                 * @param ele
                 */
                function addOddsField(ele) {
                    var html = '<input class="odds-field btn btn-small btn-default" name="odds" type="text" value="Enter odds">';
                    var input = $(html);

                    ele.parent()
                        .append(input);

                    $('.odds-field').focus(function() {
                        $(this).val('');
                    })
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

                    addTooltip(ele);
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