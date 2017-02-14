// require.js looks for the following global when initializing
var require = {
    baseUrl: "/",
    paths: {
        "jquery": "/bower_components/jquery/dist/jquery",
        "tablesorter": "/bower_components/tablesorter/dist/js/jquery.tablesorter.min",
        "bootstrap": "/bower_components/bootstrap/dist/js/bootstrap.min",
        "vue": "/bower_components/vue/dist/vue",
        "form_alternative_ele": "/js/form/alternative-option",
        "fraction": "/bower_components/fraction.js/fraction.min",
        "component_bet_entry": "/js/vuejs/components/BetEntry",
        "component_form_alt_option": "/js/vuejs/components/form/AlternativeOption",
        "model_bet_bet_line": "/js/bet/model/BetLine",
        "component_bet_bet_line": "/js/bet/BetLine",
        "text" : "/bower_components/requirejs-text/text",
    },
    shim: {
        "bootstrap": { deps: ["jquery"] },
        "form_alternative_ele": { deps: ["jquery", "bootstrap"] },
        "form_bet_value": { deps: ["jquery", "bootstrap"]},
        "tablesorter": { deps: ["jquery"] }
    }
};