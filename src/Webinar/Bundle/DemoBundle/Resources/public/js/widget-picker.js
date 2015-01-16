/*global define*/
define(['orodashboard/js/widget-picker', 'oroui/js/mediator', 'routing'],
    function (WidgetPicker, mediator, routing) {
        'use strict';

        WidgetPicker.columnOneWidgets = ['my_contacts_activity', 'my_accounts_activity'];
        WidgetPicker._onClickAddToDashboard = function(event){
            var $control = $(event.target);
            if ($control.hasClass('disabled')) {
                return;
            }

            this.widgetName = $control.data('widget-name')

            if (this.columnOneWidgets.indexOf(this.widgetName) >= 0) {
                this.targetColumn = 1;
            } else {
                this.targetColumn = 0;
            }

            var widgetContainer = $control.parents('.dashboard-widget-container');
            var controls = event.data.controls;
            var self = this;
            this._startLoading(controls, widgetContainer);
            $.post(
                routing.generate('oro_api_post_dashboard_widget_add_widget'),
                {
                    widgetName: $control.data('widget-name'),
                    dashboardId: this.dashboardId,
                    targetColumn: this.targetColumn
                },
                function (response) {
                    mediator.trigger('dashboard:widget:add', response);
                    self._endLoading(controls, widgetContainer);
                }, 'json'
            );
        };

        return WidgetPicker;
    });
