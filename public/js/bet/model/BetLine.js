define([], function() {

        /**
         * BetLine model.
         *
         * @constructor
         */
        function BetLine() {
            this.id= null;
            this.bet = null;
            this.match= null;
            this.name = null;
            this.odds= null;
            this.selection = null;
            this.win= null;
        };

        /**
         * Returns factory
         */
        return {
            create : function() {
                return new BetLine();
            }
        };
    }
);