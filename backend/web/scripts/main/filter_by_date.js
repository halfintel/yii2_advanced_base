document.addEventListener("DOMContentLoaded", function () {
    
    $(document).on('click', '.filter_by_date_last_day', function () {

        const form = $(this).closest('.filter_by_date_form');

        const inputs = form.find('input:visible');

        inputs.eq(0).val(getDateCurrent());

        inputs.eq(1).val(getDateCurrent());

        form.find('.filter_by_date_search').trigger('click');
    });

    $(document).on('click', '.filter_by_date_last_week', function () {

        const form = $(this).closest('.filter_by_date_form');

        const inputs = form.find('input:visible');

        inputs.eq(0).val(getDateOneWeekAgo());

        inputs.eq(1).val(getDateCurrent());

        form.find('.filter_by_date_search').trigger('click');
    });

    $(document).on('click', '.filter_by_date_last_month', function () {

        const form = $(this).closest('.filter_by_date_form');

        const inputs = form.find('input:visible');

        inputs.eq(0).val(getDateOneMonthAgo());

        inputs.eq(1).val(getDateCurrent());

        form.find('.filter_by_date_search').trigger('click');
    });

    $(document).on('click', '.filter_by_date_all', function () {

        const form = $(this).closest('.filter_by_date_form');

        const inputs = form.find('input:visible');

        inputs.eq(0).val('');

        inputs.eq(1).val('');

        form.find('.filter_by_date_search').trigger('click');
    });
});


function getDateCurrent() {

    const date = new Date();

    return getDateFormat(date);
}

function getDateOneWeekAgo() {

    const now = new Date();

    const date = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);

    return getDateFormat(date);
}

function getDateOneMonthAgo() {

    const now = new Date();

    const date = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());

    return getDateFormat(date);
}

function getDateFormat(date) {

    let month = date.getMonth() + 1;

    if (month < 10) {
        month = '0' + month;
    }

    let day = date.getDate();

    if (day < 10) {
        day = '0' + day;
    }

    return date.getFullYear() + '-' + month + '-' + day;
}