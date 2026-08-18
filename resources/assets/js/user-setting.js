$(function () {
  var $root = $("meta[name='root-url']").attr("content");
  $("#change-avatar").click(function () {
    $("#browse-image").trigger("click");
  });

  $("#browse-image").change(function () {
    readUrl(this);
  });

  function readUrl(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var image = input.files[0];

      reader.onload = function (e) {
        $("#avatar-crop").attr("src", e.target.result);
      };

      reader.readAsDataURL(input.files[0]);

      $("#cropModal").modal();

      var $image = $(".modal-body  img#avatar-crop");

      $("#cropModal")
        .on("shown.bs.modal", function () {
          $image.cropper({
            viewMode: 1,
            minCropBoxWidth: 300,
            minCropBoxHeight: 300,
            mouseWheelZoom: false,
            autoCropArea: 0,
            aspectRatio: 1 / 1,
          });
        })
        .on("hidden.bs.modal", function () {
          $("button#change-avatar")
            .css("background-color", "rgba(0, 0, 0, 0.62)")
            .css("color", "white");

          $("button#change-avatar").html(
            "<i class='fa fa-spinner fa-pulse'></i>"
          );

          var cropBoxData = $image.cropper("getData");

					var rect = {
						left: cropBoxData.x,
						top: cropBoxData.y,
						width: cropBoxData.width,
						height: cropBoxData.height,
					};

          cropFile(image, rect, function (url) {
            var fullUrl = `${$root}/media/images/small/${url}`;

            $image.cropper("destroy");

            $("button#change-avatar")
              .css("background-color", "transparent")
              .css("color", "transparent");

            $("button#change-avatar").html("<i class='fa fa-camera'></i>");
            $("#avatar-preview").attr("src", fullUrl);
          });
        });
    }
  }

  function cropFile(file, rect, callback) {
		var left = rect.left;
		var top = rect.top;
		var width = rect.width;
		var height = rect.height;

		var data = new FormData();
		
    data.append("file", file);
    data.append("left", left);
    data.append("top", top);
    data.append("width", width);
    data.append("height", height);
    data.append("type", "avatar");

    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });

    $.ajax({
      data: data,
      url: $root + "/api/v1/media/upload",
      type: "POST",
      cache: false,
      contentType: false,
      processData: false,
      success: callback,
    });
  }

  $("#btn-check-username").click(function () {
    check_username($("#username").val(), function (data) {
      if (data["status"]) {
        $("#alert-username")
          .attr("class", "text-success")
          .fadeIn()
          .html(data["message"]);
      } else {
        $("#alert-username")
          .attr("class", "text-danger")
          .fadeIn()
          .html(data["message"]);
      }
    });
  });

  $("#form-setting").submit(function (e) {
    e.preventDefault();

    datas = new FormData($(this)[0]);

    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });

    $.ajax({
      data: datas,
      url: $root + "/api/v1/user/update-setting",
      type: "POST",
      cache: false,
      contentType: false,
      processData: false,
      success: function (data) {
        if (data["status"]) {
          swal("Sukses", data["messages"], "success");
        } else {
          var fullmessages;

          for (var item in data["messages"]) {
            fullmessages = fullmessages + ", " + item;
          }

          swal("Whoops!", fullmessages["messages"], "error");
        }
      },
    });
  });

  $("#btn-change-cover").click(function () {
    $("#progressglobal > .progress-bar")
      .attr("aria-valuenow", 0)
      .attr("style", "width: " + 0 + "%;");

    $("#browse-cover").trigger("click");
  });

  $("#browse-cover").change(function () {
    var image = $(this).prop("files")[0];
    var data = new FormData();

    data.append("file", image);
    data.append("type", "cover");

    uploadCover(data, $("#progressglobal"), function (url) {
      var fullUrl = `${$root}/media/images/large/${url}`;

      $(".user-cover").attr("style", `background-image:url(${fullUrl})`);
      $("#cover").val(url);
    });
  });

  function uploadCover(file, progressbar, callback) {
    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
		});

    $.ajax({
      data: file,
      url: $root + "/api/v1/media/upload",
      type: "POST",
      cache: false,
      contentType: false,
      processData: false,
      xhr: function () {
				var myXhr = $.ajaxSettings.xhr();

        if (myXhr.upload) {
          myXhr.upload.addEventListener("progress", progresscover, false);
				}

        return myXhr;
      },
      success: callback,
    });
  }

  function progresscover(e) {
    if (e.lengthComputable) {
      var max = e.total;
      var current = e.loaded;
			var percentage = (current * 100) / max;

      $("#progressglobal > .progress-bar")
        .attr("aria-valuenow", percentage)
        .attr("style", "width: " + percentage + "%;");
    }
  }
});
