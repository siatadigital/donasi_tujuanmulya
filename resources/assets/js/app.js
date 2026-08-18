window.fbAsyncInit = function() {
  FB.init({
    appId      : '855295977873248',
    xfbml      : true,
    version    : 'v2.4'
  });
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

 $(function(){
    var $asset_url = $("meta[name='asset-url']").attr('content');
    var $root = $("meta[name='root-url']").attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $("#btn-check-username").click(function(){
        //check username
        check_username($("#username").val(),function(data){
            if(data['status']){
                $("#alert-username").attr("class","text-success").fadeIn().html(data['message']);
            }else{
                $("#alert-username").attr("class","text-danger").fadeIn().html(data['message']);
            }
        });
    });

    // Filter project
    $("#btnFilter").click(function() {
        var sort = $("#sort").val(),
            location = $("#location").val(),
            category = $("#category").val();
        window.location.assign("/projects?sort=" + sort + "&kategory=" + category + "&lokasi=" + location);
    })

    $(".photo-del").click(function(e){
        var theId = $(this).data("id");
        swal({
            title: "Are you sure?",
            text: "This photo maybe related with others like photo profile, cover, and blog post.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false },
            function(){
                deletePhoto(theId,function(){
                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    $(this).parent(".media-photo-block").remove();
                });
            });
    })

    function deletePhoto(id,callback)
    {
        $.ajax({
            url: $root + "/api/v1/media/" + id + "/delete",
            type: 'DELETE',
            success: function(data)
            {
                return callback(1);
            }
        })
    }

    function check_username(username,thecallback)
    {
        var data = new FormData();
        data.append("username",username);
        $.ajax({
            data: data,
            url: $root + "/api/v1/user/check-username",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data)
            {
                thecallback(data);
            }
        });
    }

    var $summer = $(".summernote");
    $("#title").keyup(function(){
        $("#title-preview").html($(this).val());
    });

    $summer.summernote({
        height: 400,
        toolbar: [
            ['style', ['style','bold', 'italic', 'underline', /*'clear'*/]],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            // ['height', ['height']],
            ['insert', ['link', 'picture', 'video', 'hr']],
            ['plugin', ['hello', 'helloImage', 'helloDropdown']],
            ['misc', ['fullscreen']],
        ],
        onImageUpload: function(files, editor, $editable)
        {
            upload_file(files[0]);
        }
    });

    $("#btn-poster").click(function() {
        $("#upload-poster").trigger('click');
    })

    $("#upload-poster").change(function() {
        upload_poster(this.files[0],function(url){
            $("#btn-poster").html("Change poster").prop("disabled",false);
            $("#poster-preview").attr("src",$root + "/media/images/large/" + url).fadeIn(100);
            $("#poster").val(url);
            // console.log(url);
        });
    });

    function upload_poster(file,callback)
    {
        $("#btn-poster").html("uploading ..").prop("disabled","true");
        data = new FormData();
        data.append("file",file)
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            data: data,
            url: $root + "/api/v1/media/upload",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url){
                return callback(url);
            }
        });
    }

    function upload_file(file)
    {
        data = new FormData();
        data.append("file",file);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            data: data,
            url: $root + "/api/v1/media/upload",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            success: function(url)
            {
                $summer.summernote('editor.insertImage',$root + "/media/images/large/" + url);
            }
        });
    }


    //Ajax change password
    $("#changePasswordForm").submit(function(e){
        e.preventDefault();
        data = new FormData();
        data.append('current_password',$("#current_password").val());
        data.append('password',$("#password").val());
        data.append('password_confirmation',$("#password_confirmation").val());
        changePassword(data,function(data){
            if(data.status)
            {
                swal("Sukses",data.messages,"success");
            }else{
                swal("Error",data.messages,"error");
            }
        })
    });

    function changePassword(data,thecallback)
    {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            data: data,
            url: $root + "/api/v1/user/resetPassword",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data)
            {
                return thecallback(data);
            }
        });
    }

    $("#youtube-url").change(function(){
        parseYoutubeUrl($(this).val());
    });

    $("#youtube-url").click(function(){
        parseYoutubeUrl($(this).val());
    });

    function parseYoutubeUrl(url)
    {
        url = url.replace("watch?v=","embed/");
        $("#youtube-url").val(url);
    }

    var $progressglobal = $("#progressglobal.progress>.progress-bar");
 });
function fbShare(obj) {
    window.open("http://www.facebook.com/sharer.php?u=" + $(obj).data("url"),"Sharer","width=600,height=300");
}

function twShare(obj) {
    window.open("https://twitter.com/home?status=" + $(obj).data("url"),"Sharer","width=600,height=300");
}

function googleShare(obj) {
    window.open("https://plus.google.com/share?url=" + $(obj).data("url"),"Sharer","width=600,height=300");
}