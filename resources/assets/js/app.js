require('bootstrap');

require('./bootstrap');

require('tinymce');
require('tinymce/icons/default');
require('tinymce/themes/silver');
require('tinymce/plugins/paste');
require('tinymce/plugins/textcolor');
require('tinymce/plugins/link');

require('@fancyapps/fancybox');

window.TrainingFiles = require('./scripts/TrainingFiles')

$(document).ready(function () {
    tinymce.init({
        selector: ".tiny",
        skin: false,
        height: 250,
        menubar: false,
        plugins: [
            'paste', 'link'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        content_css: []
    });

    $('input[name=name]').on('keyup', function () {
        let slugFiled = $(this).closest('form').find('input[name=slug]'),
            isUpdateForm = $(this).closest('form').find('input[type=hidden][name=_method]').val();

        if (!slugFiled.length || isUpdateForm === 'PATCH') {
            return;
        }

        slugFiled.val(convertToSlug($(this).val()))
    });
});

function convertToSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
}
