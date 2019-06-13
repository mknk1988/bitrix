function Add() {
    var name = $("#cat-new-name").val();
    console.log(name);
    var formData = new FormData();
    formData.append("action", "add");
    formData.append("name", name);

    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        data: formData,
        success: function() {
            window.location.reload();
        },
        error: function (request, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
}

function Delete(id) {
    var formData = new FormData();
    formData.append("id", id);
    formData.append("action", 'delete');

    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        data: formData,
        success: function() {
            window.location.reload();
        },
        error: function (request, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
}

function dataRename(id, name) {
    $("input[name='edit-id']").val(id);
    $("input[name='edit-new-name']").val(name);
    $("#avatar-modal").modal("show");
}

function Rename() {
    var id = $("#edit-id").val();
    var name = $("#edit-new-name").val();
    var formData = new FormData();
    formData.append("id", id);
    formData.append("name", name);
    formData.append("action", 'rename');

    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        data: formData,
        success: function() {
            window.location.reload();
        },
        error: function (request, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
}