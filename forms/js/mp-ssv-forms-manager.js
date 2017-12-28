(function ($) {
    $(document).ready(function () {
        let $label = $('#title-prompt-text');
        let $input = $('#title');
        $label.focus(function () {
            $(this).hide();
        });
        $input.focus(function () {
            $label.hide();
        });
        $input.focusout(function () {
            if ($input.val() === '') {
                $label.show();
            }
        });
    });
})(jQuery);
