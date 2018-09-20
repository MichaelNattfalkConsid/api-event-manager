ImportEvents = ImportEvents || {};
ImportEvents.Admin = ImportEvents.Admin || {};

ImportEvents.Admin.Fields = (function ($) {

    function Fields() {
        $(document).ready(function () {
            this.syncCheckBox();
            this.mainOrganizerCheckBox();
            this.locationGmaps();
            this.eventDatepickerRange();
            this.eventDateExceptions();
            this.duplicateOccasion();

            // Remove .button-primary from acf-buttons
            $('.acf-button').removeClass('button-primary');
            $('.acf-gallery-add').text(eventmanager.add_images);
        }.bind(this));
    }

    /**
     * Adds a button to duplicate occasions
     */
    Fields.prototype.duplicateOccasion = function () {
        $('.acf-field-57611277ef032').each(function () {
            $(this).after('<div class="acf-field"><a href="#" class="duplicateOccasion button">' + eventmanager.duplicate_occasion + '</a></div>');
        });

        $(document).on('click', '.duplicateOccasion', function (e) {
            e.preventDefault();
            $('a[data-event="add-row"]', '.acf-field-5761106783967').last().trigger('click');

            var $target = $(e.target).closest('.acf-row'),
                startDate = $('div[data-name="start_date"] .hasDatepicker', $target).val(),
                endDate = $('div[data-name="end_date"] .hasDatepicker', $target).val(),
                doorTime = $('div[data-name="door_time"] .hasDatepicker', $target).val(),
                status = $('div[data-name="status"] input:checked', $target).val(),
                exceptionInfo = $('div[data-name="occ_exeption_information"] input', $target).val(),
                contentMode = $('div[data-name="content_mode"] input:checked', $target).val(),
                tinyMceId = $('textarea[class="wp-editor-area"]', $target).attr('id'),
                tinyeMceContent = '';

            if (typeof tinyMCE !== 'undefined') {
                tinyeMceContent = tinymce.get(tinyMceId).getContent();
            }

            // Update the new occasion with cloned values
            setTimeout(function () {
                var $clonedRow = $('[data-name="occasions"] table tbody').children('tr.acf-row:not(.acf-clone)').last(),
                    newTinyMceId = $('textarea[class="wp-editor-area"]', $clonedRow).attr('id');

                $('div[data-name="start_date"]', $clonedRow).find('.hasDatepicker, .input-alt').val(startDate);
                $('div[data-name="end_date"]', $clonedRow).find('.hasDatepicker, .input-alt').val(endDate);
                $('div[data-name="door_time"]', $clonedRow).find('.hasDatepicker, .input-alt').val(doorTime);
                $('input[value="' + status + '"]', $clonedRow).attr('checked', 'checked').trigger('change');
                $('div[data-name="occ_exeption_information"] input', $clonedRow).val(exceptionInfo);
                $('input[value="' + contentMode + '"]', $clonedRow).attr('checked', 'checked').trigger('change');

                if (typeof tinyMCE !== 'undefined') {
                    tinymce.get(newTinyMceId).setContent(tinyeMceContent);
                }
            }, 0);
        });
    };

    /**
     * Toggle sync event, adds "blocking" elements to fields to prevent them beeing edited
     * if sync is activated
     * @return {void}
     */
    Fields.prototype.syncCheckBox = function () {
        $('#sync-meta-box input[type="checkbox"]').on('change', function () {
            if ($('#sync-meta-box input[type="checkbox"]').is(':checked')) {
                $('body').addClass('no-sync');
                $(this).parent().addClass('check_active');
                return;
            }

            $('body').removeClass('no-sync');
            $(this).parent().removeClass('check_active');
        }).trigger('change');
    };

    /**
     * Hides "main organizer" checkbox for other orngaizer rows than the one that's checked
     * @return {void}
     */
    Fields.prototype.mainOrganizerCheckBox = function () {
        $('.acf-field[data-name="main_organizer"] input[type="checkbox"]').each(function (i, obj) {
            if ($(this).prop('checked')) {
                $('.acf-field[data-name="main_organizer"]').not($(this).closest('.acf-field[data-name="main_organizer"]')).addClass('main_organizer_hidden');
            }
        });

        $(document).on('click', '.acf-field[data-name="main_organizer"] input[type="checkbox"]', function () {
            if ($(this).prop('checked')) {
                $('.acf-field[data-name="main_organizer"]').not($(this).closest('.acf-field[data-name="main_organizer"]')).addClass('main_organizer_hidden');
            } else {
                $('.acf-field[data-name="main_organizer"]').removeClass('main_organizer_hidden');
            }
        });
    };

    /**
     * Hide Google map on post type location if address data is missing
     * @return {void}
     */
    Fields.prototype.locationGmaps = function () {
        $('.acf-field[data-name="geo_map"] .acf-hidden').each(function (i, obj) {
            var address = $(this).find('[data-name="address"]').val();
            var lat = $(this).find('[data-name="lat"]').val();
            var lng = $(this).find('[data-name="lng"]').val();
            if (!address || !lat || !lng) {
                $('.acf-field[data-name="geo_map"]').hide();
            }
        });
    };

    /**
     * Limits datepickers for endtime and door time according to the starttime
     * @return {void}
     */
    Fields.prototype.eventDatepickerRange = function () {
        $.datepicker.setDefaults({
            minDate: 'now',
            maxDate: new Date().getDate() + 365
        });

        $(document).on('click', '.acf-field-576110e583969 .hasDatepicker, .acf-field-5761169e07309 .hasDatepicker', function () {
            var date = $(this).parentsUntil('.acf-fields').siblings('.start_date').find('.hasDatepicker').val();

            if (!date) {
                return;
            }

            var d = date.split(/[ ]+/);
            var r = d[0].split(/[-]+/);
            var m = r[1] - 1;

            date = new Date(r[0], m, r[2], 0, 0, 0);

            if (Object.prototype.toString.call(date) === "[object Date]") {
                if (!isNaN(date.getTime())) {
                    var year = date.getFullYear();
                    var month = date.getMonth();
                    var day = date.getDate();

                    if ($(this).parents('[data-name="end_date"]').length) {
                        var end_date = new Date(year + 1, month, day);
                        $(this).datetimepicker("option", {
                            minDate: date,
                            maxDate: end_date,
                            defaultDate: date
                        });
                    }

                    if ($(this).parents('[data-name="door_time"]').length) {
                        var start_date = new Date(year - 1, month, day);
                        $(this).datetimepicker("option", {
                            minDate: start_date,
                            maxDate: date,
                            defaultDate: date
                        });
                    }
                    $(this).datetimepicker("show");
                }
            }
        });
    };

    /**
     * Show recurring rules exeptions in date picker
     * @return {void}
     */
    Fields.prototype.eventDateExceptions = function () {
        $(document).on('click', '.acf-field-57d279f8db0cc .hasDatepicker', function (e) {
            $this = $(e.target).closest('.hasDatepicker');

            $this.datepicker('option', 'dateFormat', 'yy-mm-dd');

            var weekDay = $this.parents('.acf-field-repeater').siblings('[data-name="rcr_week_day"]').find('select').val(),
                startDate = $this.parents('.acf-field-repeater').siblings('[data-name="rcr_start_date"]').find('.hasDatepicker').val(),
                endDate = $this.parents('.acf-field-repeater').siblings('[data-name="rcr_end_date"]').find('.hasDatepicker').val(),
                weekInterval = $this.parents('.acf-field-repeater').siblings('[data-name="rcr_weekly_interval"]').find('input[type="number"]').val();
                weekInterval = weekInterval != null ? parseInt(weekInterval) : 1;

            $this.datepicker('option', 'defaultDate', startDate);

            if (startDate && endDate) {
                var start = this.getClosestDay(new Date(startDate), this.weekdayNumber(weekDay));
                var end = new Date(endDate);

                var occurances = [];

                for (var dat = new Date(start); dat <= end; dat.setDate(dat.getDate() + (weekInterval * 7))) {
                    occurances.push(this.formattedDate(new Date(dat)));
                }

                var disableSepcificDate = function (date) {
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    return [occurances.indexOf(string) != -1];
                };

                $this.datepicker('option', 'beforeShowDay', disableSepcificDate);
            }

            $this.datepicker({showOn: 'focus'}).focus();
        }.bind(this));
    };

    /**
     * Gets the closest date that matches a day of week
     * @param  {Date} date        Date object
     * @param  {[type]} dayOfWeek [description]
     * @return {[type]}           [description]
     */
    Fields.prototype.getClosestDay = function (date, dayOfWeek) {
        var resultDate = new Date(date.getTime());
        resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getDay()) % 7);

        return resultDate;
    };

    /**
     * Formats date to yyyy-mm-dd
     * @param  {Date} date
     * @return {string}
     */
    Fields.prototype.formattedDate = function (date) {
        var curr_date = ('0' + date.getDate()).slice(-2);
        var curr_month = ('0' + (date.getMonth() + 1)).slice(-2);
        var curr_year = date.getFullYear();

        return curr_year + "-" + curr_month + "-" + curr_date;
    };

    /**
     * Converts weekday in string to weekday number
     * @param  {string} weekdayString Weekday as string
     * @return {int}                  Weekday as int
     */
    Fields.prototype.weekdayNumber = function (weekdayString) {
        var weekday = [];

        weekday.monday = 1;
        weekday.tuesday = 2;
        weekday.wednesday = 3;
        weekday.thursday = 4;
        weekday.friday = 5;
        weekday.saturday = 6;
        weekday.sunday = 7;

        if (typeof weekday[weekdayString.toLowerCase()] !== 'undefined') {
            return weekday[weekdayString.toLowerCase()];
        }

        return 0;
    };

    return new Fields();

})(jQuery);
