define(
    [
        "vue",
        "jquery",
        "bootstrap"
    ],
    function(Vue, jQuery) {

        var AlternativeOption = Vue.extend({
            /**
             * The template.
             */
            template: '<div class="modal fade {{modal_class}}" tabindex="-1" role="dialog">'+
            '                <div class="modal-dialog">'+
            '                    <div class="modal-content">'+
            '                        <div class="modal-header">'+
            '                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
            '                            <h4 class="modal-title">{{ title }}</h4>'+
            '                        </div>'+
            '                        <div class="modal-body">'+
            '                            <select name="alt-value" v-model="select" class="form-control"><optgroup v-for="group in options" label="{{group.label}}"><option v-for="opt in group.options" value="{{opt}}">{{opt}}</option></optgroup></select>'+
            '                        </div>'+
            '                        <div class="modal-footer">'+
            '                            <button type="button" v-on:click="updateInput" class="btn btn-primary alt-select">Select</button>'+
            '                        </div>'+
            '                    </div>'+
            '                </div>'+
            '            </div>'+
            '            <button class="btn btn-small btn-default show-alt-option" v-on:click="showModal"> <i class="fa fa-list-ol"></i></button>',

            /**
             * Properties which can be passed in
             * via HTML attributes.
             */
            props: [
                'options',
                'title',
                'modal_class',
                'target'
            ],

            /**
             *
             * @returns {{modal: null}}
             */
            data:function () {
                return {
                    modal  : null,
                    select : null
                }
            },

            /**
             *
             */
            ready : function() {
                this.modal = jQuery('.' + this.modal_class);
            },

            /**
             * Methods
             */
            methods : {
                /**
                 * Calculates the return value from the
                 * bet amount & odds.
                 */
                showModal : function(e) {
                    e.preventDefault();
                    this.modal.modal('show');
                },

                updateInput : function(e) {
                    e.preventDefault();

                    var inputSelector = 'input[name=\'' + this.target + '\']';

                    jQuery(this.$root.$el).find(inputSelector).val(this.select);

                    this.modal.modal('hide');
                }
            }
        })

        Vue.component('alt-option', AlternativeOption);

        return AlternativeOption;
    }
);