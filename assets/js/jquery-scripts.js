

$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
    });
});


$(document).ready(function () {
    $('#open').click(function () {
        $('#track').modal('show');
    });     
});
$(document).ready(function () {
    $('#track').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close').click(function () {
        $('#track').modal('hide');
    });     
});  


 $(document).ready(function () {
    $('.open-add').click(function () {
        $('#add').modal('show');
    });     
});   

$(document).ready(function () {
    $('#add').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-add').click(function () {
        $('#add').modal('hide');
    });     
});   


$('.open-edit').click(function () {
    $('#edit').modal('show');
    console.log($(this).val());
    var arr = $(this).val().split("/");
    
    $('#studentNo1').val(String(arr[0]));
    $('#lastName1').val(String(arr[1]));
    $('#firstName1').val(String(arr[2]));
    $('#middleName1').val(String(arr[3]));
    $('#birthday1').val(String(arr[4]));
    $('#contact1').val(String(arr[5]));
    $('select[name="course1"] option[value="'+String(arr[6])+'"]').attr('selected', 'selected');
    $('select[name="year1"] option[value="'+String(arr[7])+'"]').attr('selected', 'selected');
    $('#section1').val(String(arr[8]));
}); 


$(document).ready(function () {
    $('#edit').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit').click(function () {
        $('#edit').modal('hide');
        window.location.href = 'students.php';
    });     
});


$(document).ready(function () {
    $('#change-profile').click(function () {
        $('#upload-panel').modal('show');
    });     
});

$(document).ready(function () {
    $('#btn-edit-name').click(function () {
        $('#edit-name').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-name').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-name').click(function () {
        $('#edit-name').modal('hide');
        window.location.href = 'profile.php';
    });     
});

$(document).ready(function () {
    $('#btn-edit-email').click(function () {
        $('#edit-email').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-email').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-email').click(function () {
        $('#edit-email').modal('hide');
        window.location.href = 'profile.php';
    });     
});

$(document).ready(function () {
    $('#btn-edit-username').click(function () {
        $('#edit-username').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-username').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-username').click(function () {
        $('#edit-username').modal('hide');
        window.location.href = 'profile.php';
    });     
});

$(document).ready(function () {
    $('#btn-edit-password').click(function () {
        $('#edit-password').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-password').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-password').click(function () {
        $('#edit-password').modal('hide');
        window.location.href = 'profile.php';
    });     
});
function previewProfilePicture(input){
    var image = $("input[type=file]").get(0).files[0];

    if(image){
        var read = new FileReader();

        read.onload = function(){
            $("#preview-profile").attr("src", read.result);
        }
        read.readAsDataURL(image);
    }
}

$(document).ready(function () {
    $('.open-delete').click(function () {
        $('#delete').modal('show');
    });     
});   

$(document).ready(function () {
    $('#delete').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('#btn-edit-gender').click(function () {
        $('#edit-gender').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-gender').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-gender').click(function () {
        $('#edit-gender').modal('hide');
        window.location.href = 'profile.php';
    });     
});


$(document).ready(function () {
    $('#btn-edit-birthday').click(function () {
        $('#edit-birthday').modal('show');
    }); 
});

$(document).ready(function () {
    $('#edit-birthday').modal({
        backdrop: 'static',
        keyboard: false
    });
});

$(document).ready(function () {
    $('.close-edit-birthday').click(function () {
        $('#edit-birthday').modal('hide');
        window.location.href = 'profile.php';
    });     
});

$('.open-edit1').click(function () {
    $('#edit').modal('show');
    var arr = $(this).val().split("/");
    console.log(arr);
    $('#employeeNo1').val(String(arr[0]));
    $('#lastName1').val(String(arr[1]));
    $('#firstName1').val(String(arr[2]));
    $('#middleName1').val(String(arr[3]));
    $('#birthday1').val(String(arr[4]));
    $('#email1').val(String(arr[5]));
    $('select[name="gender1"] option[value="'+String(arr[6])+'"]').attr('selected', 'selected');

}); 

