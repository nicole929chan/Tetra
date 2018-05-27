require('leaflet');
require('leaflet-draw');

$(document).ready(function(){

  //關閉底部
  var selection_height = 0;
  $("#bottom_off").click(function(event){
    event.preventDefault();
    selection_height = $("#view_select").height();

    $("#view_select-open").css('display', 'block');
    $("#bottom-tool").stop(true).animate({ opacity: '0'}, 'slow', function(){
      $("#bottom-tool").css('display', 'none');
    });
  });

  //展開底部
  $("#view_select_on").click(function(event){
    event.preventDefault();
    $("#view_select-open").css('display', 'none');
    $("#bottom-tool").css('display', 'table');
    $("#bottom-tool").stop(true).animate({height: selection_height, opacity: '1'}, 'slow', function(){
    });
  });

  // 下拉選單切換到不同room的已經選擇的view selection
  $("#rooms").change(function (e) {
    location.href = `/rooms/${$(this).val()}/selection`;
  })

});
