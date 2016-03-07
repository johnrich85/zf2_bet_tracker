require(
    [
        'jquery',
        "form_alternative_ele",
        "form_bet_value"
    ],
    function($) {
        $(document).ready(function() {
            $("#match-name").alternativeOption({});
            $("#bet-value").betValue({});
        })
    }
);