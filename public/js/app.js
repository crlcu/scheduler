jQuery(document).ready(function($) {
    $('input[provide="datetimepicker"]:not(.js-has-datetimepicker)').attr('autocomplete', 'off')
        .addClass('js-has-datetimepicker')
        .datetimepicker({
            format: "Y-m-d H:i:00",
            todayBtn: "linked",
            todayHighlight: true,
            step: 1,
        });

    $(document).on('mouseover', 'input[provide="datetimepicker"]:not(.js-has-datetimepicker)', function() {
        $(this)
            .attr('autocomplete', 'off')
            .addClass('js-has-datetimepicker')
            .datetimepicker({
                format: "Y-m-d H:i:00",
                todayBtn: "linked",
                todayHighlight: true,
                step: 1,
            });
    });

    $('.modal-trigger').leanModal();
});
