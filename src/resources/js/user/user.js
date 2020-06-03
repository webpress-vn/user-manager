import $ from 'jquery';

$(document).ready(function() {
    var showPass = 0;
    $('.btn-show-pass').on('click', function() {
        if (showPass == 0) {
            $(this).next('input').attr('type', 'text');
            $(this).find('i').removeClass('fa-eye');
            $(this).find('i').addClass('fa-eye-slash');
            showPass = 1;
        } else {
            $(this).next('input').attr('type', 'password');
            $(this).find('i').addClass('fa-eye');
            $(this).find('i').removeClass('fa-eye-slash');
            showPass = 0;
        }
    });
    var button    = document.getElementById('user_component_show_edit_form');
    var edit_form = document.getElementById('user_component_form_edit_info');
    var info_form = document.getElementById('user_component_form_show_info');

    if (button) {
        edit_form.style.display = "none";
        info_form.style.display = "block";
        button.onclick = function() {
            edit_form.style.display = "block";
            info_form.style.display = "none";
        };
    }

});
