define(['jquery'], function($) {
    'use strict';

    /**
     * @export  orocrm/partner/widgets/github-accounts-widget
     * @class   oro.AccountContactWidgetHandler
     */
    return {
        /**
         * @desc Fire name link click
         * @callback
         */
        boxClickHandler: function(even) {
            /**
             * @desc if target item has class githubaccount-box-link
             * we does not click redirection link(name link)
             */
            if ($(even.target).hasClass('githubaccount-box-link')) {
                return;
            }
            var url = $(this).find('.githubaccount-box-username-link').attr('href');
            if (url) {
                window.open(url, '_blank');
            }
        },

        /**
         * @constructs
         */
        init: function() {
            $('.githubaccount-box').click(this.boxClickHandler);
        }
    };
});
