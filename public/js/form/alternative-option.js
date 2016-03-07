(function ($) {
    $.extend({
        alternativeOption: new
            function () {

                /**
                 * Finds and returns button.
                 *
                 * @param formGroup
                 * @returns {*}
                 */
                function getButton(formGroup) {
                    var button = formGroup.find('.show-alt-option');

                    return button.first();
                }

                /**
                 * Find and returns modal.
                 *
                 * @param formGroup
                 * @returns {*}
                 */
                function getModal(formGroup) {
                    var modal = formGroup.find('.modal');

                    return modal.first();
                }

                /**
                 * Adds event handler to button.
                 *
                 * @param button
                 * @param modalEle
                 */
                function addButtonClickHandler(button, modalEle) {
                    button.click(function(e) {
                        e.preventDefault();
                        modalEle.modal('show');
                    })
                }

                /**
                 * Updates field value and hides modal.
                 *
                 * @param modalEle
                 * @param input
                 */
                function addModalSelectHandler(modalEle, input) {
                    var button = modalEle.find('.alt-select');

                    button.click(function(e) {
                        e.preventDefault();

                        var value = modalEle.find('[name=alt-value]').val();

                        input.val(value);

                        modalEle.modal('hide');
                    })
                }

                /**
                 * Creates alternativeOption instance.
                 *
                 * @param settings
                 */
                this.construct = function (settings) {
                    var ele = $(this);

                    var button = getButton(ele);
                    var modalEle = getModal(ele);
                    var input = ele.find('input').first();

                    addButtonClickHandler(button, modalEle);

                    addModalSelectHandler(modalEle, input);
                };
            }
    });

    // extend plugin scope
    $.fn.extend({
        alternativeOption: $.alternativeOption.construct
    });

})(jQuery);
