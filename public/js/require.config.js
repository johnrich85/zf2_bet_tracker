// require.js looks for the following global when initializing
var require = {
    baseUrl: ".",
    paths: {
        "jquery": "/bower_components/jquery/dist/jquery",
        "tablesorter": "/bower_components/tablesorter/dist/js/jquery.tablesorter.min",
        "bootstrap": "/bower_components/bootstrap/dist/js/bootstrap.min",
        "vue": "/bower_components/vue/dist/vue",
        "form_alternative_ele": "/js/form/alternative-option",
        "fraction": "/bower_components/fraction.js/fraction.min",
        "component_bet_entry": "/js/vuejs/components/bet-entry"
    }
    ,
    shim: {
        "bootstrap": { deps: ["jquery"] },
        "form_alternative_ele": { deps: ["jquery", "bootstrap"] },
        "form_bet_value": { deps: ["jquery", "bootstrap"]},
        "tablesorter": { deps: ["jquery"] }
    }
};