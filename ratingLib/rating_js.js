// Rating System 2.0 QSS
// requires JQuery

// global var
var src_img = ["img/rating_0.png", "img/rating_1.png"];
var rating_save_file = "ratingLib/rating_save.php";
var voted_text = "Voted !";
var voted_class = "voted";

jQuery(document).ready(function( $ ) {

  /**
  * Cookie Settings:
  * Check for existing Votings / Read Cookie
  **/

  function setCookie(name,value,days) {
      var expires = "";
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }

  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }

  function eraseCookie(name) {
      document.cookie = name+'=; Max-Age=-99999999;';
  }

  //check if cookie is set
  $("form").each(function( index ) {
    var itemID = $(this).children(".itemId").attr("value");
    var itemCookie = getCookie(itemID);

    if (itemCookie) {
      // set Voted Attributes
      $(this).children(".rating").attr("value", itemCookie);
      $(this).children().children(".vote-validation").html(voted_text);
      $(this).children().children( ".vote-validation" ).addClass(voted_class);
      $(this).children(".voteStatus").attr("value", "1");
      setPermanentStars($(this));
    }
  });

  /**
  * Voting Functionality
  **/

  function setPermanentStars(form) {
    var value = $( form ).children(".rating").attr("value");
    $( form ).children().children().children(".ratingStar").each(function( index ) {
      if ($(this).attr("value") <= value) {
        $(this).attr("src", src_img[1]);
      }
    });
  }

  $('.ratingStar').on("mouseenter", function(event){
    // Check if Form was already submitted
    var form = $(this).parent().parent().parent();
    if (form.children(".voteStatus").attr("value") == 1) { } else {
      $(this).attr("src", src_img[1]);
      $(this).parent().prevAll().children().attr("src", src_img[1]);
    }
  });

  $('.ratingStar').on("mouseleave", function(event){
    // Check if Form was already submitted
    var form = $(this).parent().parent().parent();
    if (form.children(".voteStatus").attr("value") == 1) { } else {
      $(this).attr("src", src_img[0]);
      $(this).parent().prevAll().children().attr("src", src_img[0]);
    }
  });

  $('.ratingStar').on("click", function(event){
    var form = $ ( "#" + $(this).attr("formID") );

    if (form.children(".voteStatus").attr("value") == 1) { } else {
      var ratingInput = form.children().first();
      var ratingValue = $(this).attr("value");

      $( ratingInput ).attr("value", ratingValue);
      $( form ).submit();
    }
  });

  $('form').on('submit', function(event){
    event.preventDefault();
    var form = $(this);
    var itemID = $(this).children('.itemId').attr("value");
    var ratingValue = $(this).children('.rating').attr("value");
    var formData = form.serialize();

    $.ajax({
      type : 'POST',
      url : rating_save_file,
      data : formData,
      success: function (response){
        console.log(response);
        if (response == "success") {

          form.children().children( ".vote-validation" ).html(voted_text);
          form.children().children( ".vote-validation" ).addClass(voted_class);
          form.children(".voteStatus").attr("value", 1);

          setPermanentStars(form);
          setCookie(itemID,ratingValue,31);
        } else {
          // console.log(response);
        }
      }
    });
  });
});