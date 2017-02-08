require(
    [
        "vue",
        "jquery",
        "component_bet_entry",
        "component_form_alt_option"
    ],
    function(Vue, $, BetEntry) {
        Vue.component('bet-entry', BetEntry);

        new Vue({el: '#bet-entry'})
    }
);