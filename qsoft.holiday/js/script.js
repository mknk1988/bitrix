let idEl;
let idImage;
$(function () {
    $('#space-toggler').click(function () {
        $('#space-toggler').css('display', 'none');
        $('#space-toggler--active').css('display', '');
    });
    $('#space-toggler--active').click(function () {
        $.ajax({
            type: 'POST',
            url: '/local/modules/qsoft.holiday/lib/ajax.php',
            dataType: 'json',
            data: {type:'delete'},
            success: function (data) {
                console.log(data.type);
                $('#img-space').attr('src', '').css('display', 'none');
                $('#space-toggler').css('display', '');
            },
            error: function () {
                console.log('ошибка');
            }
        });
        $('#space-toggler--active').css('display', 'none');
        $('#space-toggler').css('display', '');
    });
    $('#bravo__modal-close').click(function () {
        $('#space-toggler--active').css('display', 'none');
        $('#space-toggler').css('display', '');
    });
    $('.space-checkbox>input').click(function () {
        idEl = $(this).attr('id');
        idImage = $('img[id='+idEl+']').attr('name');
    });
    $('.bravo__modal-btn-submit').click(function () {
        $.ajax({
            type: 'POST',
            url: '/local/modules/qsoft.holiday/lib/ajax.php',
            dataType: 'json',
            data: {type:'space_day', id: idEl, fileId: idImage},
            success: function (data) {
                $('#img-space').attr('src', data.url).css('display', '');
                $('#space-modal').modal();
            },
            error: function () {
                console.log('ошибка');
            }
        });
    });
});
