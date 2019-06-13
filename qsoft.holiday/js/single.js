$(function () {
    $('#space-toggler').click(function () {
        $.ajax({
            type: 'POST',
            url: '/local/modules/qsoft.holiday/lib/ajax.php',
            dataType: 'json',
            data: {type:'space_day', id: idEl, fileId: idImage},
            success: function (data) {
                $('#img-space').attr('src', data.url).css('display', '');
                $('#space-toggler').css('display', 'none');
                $('#space-toggler--active').css('display', '');

            },
            error: function () {
                console.log('ошибка');
            }
        });
    });

    $('#space-toggler--active').click(function () {
        $.ajax({
            type: 'POST',
            url: '/local/modules/qsoft.holiday/lib/ajax.php',
            dataType: 'json',
            data: {type:'delete'},
            success: function (data) {
                $('#img-space').attr('src', '').css('display', 'none');
                $('#space-toggler').css('display', '');
                $('#space-toggler--active').css('display', 'none');
            },
            error: function () {
                console.log('ошибка');
            }
        });
    });
});