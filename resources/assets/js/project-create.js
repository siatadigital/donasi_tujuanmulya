$(function () {
  var $root = $("meta[name='root-url']").attr("content");
  var $active_progress = $("#progress_cover>.progress-bar");
  var editableRewardId = "";
  var isEdit = false;

  $("#title").keyup(function () {
    $("#title-preview").html($(this).val());
  });

  $("#name").keyup(function () {
    $("#user-preview").html($(this).val());
  });

  $("#btn-browse-cover-project").click(function (e) {
    e.preventDefault();
    $("#browse-cover-project").trigger("click");
  });

  $("#browse-cover-project").change(function () {
    $("#progress_cover>.progress-bar")
      .attr("aria-valuenow", 0)
      .attr("style", "width:0%;");
    $("#progress_cover").fadeIn();
    var image = $(this).prop("files")[0];
    var data = new FormData();
    data.append("file", image);
    var url = upload_image(data, $("#progress_cover"), function (url) {
      $("#cover").val(url);
      $("#cover-preview").css(
        "background-image",
        "url(" + $root + "/media/images/large/" + url + ")"
      );
    });
  });

  //Update progressbar with real process.
  function progress(e) {
    if (e.lengthComputable) {
      var max = e.total;
      var current = e.loaded;

      var Percentage = (current * 100) / max;
      $active_progress
        .attr("aria-valuenow", Percentage)
        .attr("style", "width: " + Percentage + "%;");
    }
  }

  function upload_image(file, progressbar, thecallback) {
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
          myXhr.upload.addEventListener("progress", progress, false);
        }
        return myXhr;
      },
      success: function (url) {
        progressbar.fadeOut();
        thecallback(url);
      },
    });
  }

  $("#btn-browse-thumbnail").click(function (e) {
    e.preventDefault();
    $("#browse-thumbnail").trigger("click");
  });

  $("#browse-thumbnail").change(function () {
    $("#progress_thumbnail>.progress-bar")
      .attr("aria-valuenow", 0)
      .attr("style", "width:0%;");
    $("#progress_thumbnail").fadeIn();
    var image = $(this).prop("files")[0];
    var data = new FormData();
    data.append("file", image);
    $active_progress = $("#progress_thumbnail>.progress-bar");
    var url = upload_image(data, $("#progress_thumbnail"), function (url) {
      $("#thumbnail").val(url);
    });
  });

  $("#summary").keyup(function () {
    $("#summary-preview").html($(this).val());
  });

  $("#btn-video").click(function () {
    var embed_url = $("#video").val();
    embed_url = embed_url.replace(
      "https://www.youtube.com/watch?v=",
      "https://www.youtube.com/embed/"
    );
    $("#video-preview").attr("src", embed_url);
    $("#video-campaign-preview").fadeIn();
  });

  $("#add-reward").click(function (e) {
    e.preventDefault();
    var $rewardList = $("#reward-list");
    var reward = $("#reward");
    var thumbnail = $("#thumbnail");
    var description = $("#description");
    var max_name_count = $("#max_name_count");

    if (reward.val() == "" && description.val() == "") {
      swal("Error!", "You cannot leave empty field!", "error");
    } else {
      if (!isEdit) {
        var imgClass = thumbnail.val() ? "display:block" : "display:none;";

        $rewardList.prepend(`
					<div
						class="rewards"
						data-id="${Date.now()}"
						data-price="${reward.val()}"
						data-cover="${thumbnail.val()}"
						data-content="${description.val()}"
						data-max_name_count="${max_name_count.val()}"
					>
						<input type="hidden" name="rewards[id][]" value="${Date.now()}" />
						<input type="hidden" name="rewards[price][]" value="${reward.val()}" class="form-control" />
						<input type="hidden" name="rewards[cover][]" value="${thumbnail.val()}" class="form-control" />
						<input type="hidden" name="rewards[content][]" value="${description.val()}" class="form-control" />
						<input type="hidden" name="rewards[max_name_count][]" value="${max_name_count.val()}" class="form-control" />
						<input type="hidden" name="rewards[is_new][]" value="1" class="form-control" />

						<header>
							<h4 class="rewards-price">${reward.val()}</h4>
							<h6 class="rewards-max_name_count">Max ${max_name_count.val()} Input Nama</h6>
						</header>

						<img style="margin-bottom:5px;${imgClass}" src="${`${$root}/media/images/large/${thumbnail.val()}`}" class="rewards-cover img-responsive"/>

						<p style="margin: 0 0 5px;" class="rewards-content">${description.val()}</p>
						<button type="button" class="btn btn-warning edit-reward"><i class="fa fa-pencil"></i></button>
						<button type="button" class="btn btn-danger remove-reward"><i class="fa fa-trash"></i></button>
					</div>
				`);

        reward.val("Rp 50.000");
        description.val("");
        thumbnail.val("");
        max_name_count.val("1");
      } else {
        var $reward = $rewardList.find(`.rewards[data-id=${editableRewardId}]`);

        $reward.data("price", reward.val());
        $reward.data("cover", thumbnail.val());
        $reward.data("content", description.val());
        $reward.data("max_name_count", max_name_count.val());

        $reward.find("input").eq(1).val(reward.val());
        $reward.find("input").eq(2).val(thumbnail.val());
        $reward.find("input").eq(3).val(description.val());
        $reward.find("input").eq(4).val(max_name_count.val());

        $reward.find(".rewards-price").text(reward.val());
        $reward
          .find(".rewards-max_name_count")
          .text("Max " + max_name_count.val() + " Input Nama");
        $reward
          .find(".rewards-cover")
          .attr("src", `${$root}/media/images/large/${thumbnail.val()}`);
        $reward.find(".rewards-content").text(description.val());

        if (thumbnail.val()) {
          $reward.find(".rewards-cover").show();
        } else {
          $reward.find(".rewards-cover").hide();
        }

        $("#reset-form").click();
      }
    }

    $("#finish").prop("disabled", false);
  });

  $("#reset-form").on("click", function () {
    var $form = $("#reward-form");

    $form.find("input[name=reward]").val("Rp 50.000");
    $form.find("textarea[name=description]").val("");
    $form.find("input[name=max_name_count]").val("1");
    $form.find("input[name=thumbnail]").val("");
    $form.find("#add-reward").text("Add Opsi Pilihan");
    $form.find("#reset-form").hide();

    editableRewardId = "";
    isEdit = false;
  });

  $("#reward-list").on("click", ".remove-reward", function (e) {
    e.preventDefault();
    $(this).parent(".rewards").remove();
    $("#reset-form").click();
  });

  $("#reward-list").on("click", ".edit-reward", function (e) {
    e.preventDefault();

    var $form = $("#reward-form");
    var $item = $(this).parent();
    var data = $item.data();
    console.log("data", data);

    $form.find("input[name=reward]").val(data.price);
    $form.find("textarea[name=description]").val(data.content);
    $form.find("#max_name_count").val(data.max_name_count);
    $form.find("input[name=thumbnail]").val(data.cover);
    $form.find("#add-reward").text("Ubah Opsi Pilihan");
    $form.find("#reset-form").show();

    editableRewardId = data.id;
    isEdit = true;
  });

  // count summary length
  $("#summary").keyup(function () {
    $("#summary_count").html(120 - parseInt($(this).val().length));
  });

  // handle save & continue campaign
  $("#btn-save-campaign").click(function (e) {
    e.preventDefault();
    if (
      $("#title").val() == "" ||
      $("#cover").val() == "" ||
      $("#summary").val() == "" ||
      $("#goal").val() == "" ||
      $("#campaign").val() == "" ||
      $("#startproject").val() == "" ||
      $("#endproject").val() == "" ||
      $("#category").val() == "0" ||
      $("#province").val() == "0" ||
      $("#city").val() == "0"
    ) {
      swal("Error!", "You cannot leave empty field!", "error");
    } else if (!$("#accept").is(":checked")) {
      swal("Error!", "You must aggree term & condition!", "error");
    } else {
      $("#step1").fadeOut();
      $("#step2").fadeIn();
    }
  });
});
