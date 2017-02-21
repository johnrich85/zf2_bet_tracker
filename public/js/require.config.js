// require.js looks for the following global when initializing
var require = {
    baseUrl: "../../../",
    paths: {
        "jquery": "/bower_components/jquery/dist/jquery",
        "tablesorter": "/bower_components/tablesorter/dist/js/jquery.tablesorter.min",
        "bootstrap": "/bower_components/bootstrap/dist/js/bootstrap.min",
        "vue": "/bower_components/vue/dist/vue",
        "form_alternative_ele": "/js/form/alternative-option",
        "fraction": "/bower_components/fraction.js/fraction.min",
        "text" : "/bower_components/requirejs-text/text",
        "component_form_alt_option": "/js/vuejs/components/form/AlternativeOption",

        "bet_model_line": "/js/bet/model/BetLine",
        "bet_component_line": "/js/bet/BetLine",
    },
    shim: {
        "bootstrap": { deps: ["jquery"] },
        "form_alternative_ele": { deps: ["jquery", "bootstrap"] },
        "tablesorter": { deps: ["jquery"] }
    }
};