$(document).ready(function(){
  var selection_height = 0;
  //關閉底部
  $("#view_select_off").click(function(event){
    event.preventDefault();
    selection_height = $("#view_select").height();
    $("#view_select-open").css('display', 'block');
    $("#view_select").stop(true).animate({height:0, opacity: '0'}, 'slow', function(){
      $("#view_select").css('display', 'none');
    });
  });

  //展開底部
  $("#view_select_on").click(function(event){
    event.preventDefault();
    $("#view_select-open").css('display', 'none');
    $("#view_select").css('display', 'block');
    $("#view_select").stop(true).animate({height: selection_height, opacity: '1'}, 'slow', function(){
    });
  });

  $('.selections').click(function(){
    $("#main_img").attr('rel', $(this).attr('rel'));
    $("#main_img").attr('src', $(this).children('img').attr('src'));
    $("#download").attr('href', $(this).children('img').attr('src'));
  });

  $('#feedback').click(function(){
        var main_img = $("#main_img").attr('rel');
        var url = "/rooms/selections/";

        $.ajax({
          data: main_img,
          dataType: 'json',
          type: "POST",
          url: url + main_img,
          success: function (data) {
            console.log(data);
            alert(data.messeage);
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
    });


});
