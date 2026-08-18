$(function () {
  $.fn.revealPassword = function() {
    $(this)
      .next()
      .on("click", function () {
        var type = $(this).prev().prop("type");
        var newType = type === "password" ? "text" : "password";

        $(this).prev().prop("type", newType);
      });
  };

  $('.reveal-password').revealPassword();
});
