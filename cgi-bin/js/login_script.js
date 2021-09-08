/**
 * Created by bangl on 22-Mar-18.
 */
$(function () {

    $('body').on('click','.close-form',function(event){
        $('#id01').css("display", "none");
    });
    $('body').on('click','#submit-login',function(event){
        var username = $('#usrname').val();
        var user_password = $.md5($('#user-password').val());
        var request=$.ajax({
            type:'get',
            url: site_url+"/base/validate_credential",
            data:  { username: username, user_password: user_password },
            success: function(data, textStatus, xhr) {
                var obj= $.parseJSON(data);
                if(obj.is_logged_in==1) {
                    var request=$.ajax({
                        type:'get',
                        url: site_url+"/base/show_headers",
                        data:  { person_cat_id: obj.person_cat_id },
                        success: function(data, textStatus, xhr) {

                            $('#header').html(data);
                            $('#id01').css("display", "none");
                        }
                    });// end of ajax
                    // console.log(data);
                    //$('#header').html(data);
                }else {
                    $('#usrname').css("background-color", "#ff4d4d");
                    $('#user-password').css("background-color", "#ff4d4d");
                    $('#login-error-text').text('  Check user id or password');
                }

            }
        });// end of ajax
    });


});