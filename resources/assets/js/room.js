require('leaflet');
require('leaflet-draw');

$(document).ready(function(){
  // 設定leaflet 的高度
  var mapheight = $(window).height() - 45;
  $("#mapid").css('height', mapheight);

  // 初始化 leaflet
  var osmAttrib = 'Vertify Analytics', // 版權說明變數
      osm = L.tileLayer('', {minZoom: 2, maxZoom: 5, center: [0, 0], zoom: 1, attribution: osmAttrib}); // 設定參數

  var mymap = L.map('mapid', {
    layers: [osm],
    center: [0, 0],
    zoomControl: false
  }).setView([0, 0], 4);

  //設定leaflet zoom 位置
  L.control.zoom({
       position:'topright'
  }).addTo(mymap);

  // 設定圖片大小與座標範圍
  var h = 1280,
      w = 1920,
      southWest = mymap.unproject([0, h], mymap.getMaxZoom()-1),
      northEast = mymap.unproject([w, 0], mymap.getMaxZoom()-1);
  var bounds = new L.LatLngBounds(southWest, northEast);

  // 將圖片置入 leaflet
  var url="http://freelancers3d.com/uploads/projects/bolefloor-living3_e8d33e592b149edf341d9c1078f0099e.jpg";
  L.imageOverlay(url, bounds).addTo(mymap);

  // 設定最大範圍
  mymap.setMaxBounds(bounds);

  // 設定 leaflet-draw 工具列
  var drawnItems = new L.FeatureGroup();
  mymap.addLayer(drawnItems);

  var drawControl = new L.Control.Draw({
      draw: {
        // 設定可以啟用的項目
      },
      edit: {
          featureGroup: drawnItems
      },
      position: 'bottomright',
  });
  mymap.addControl(drawControl);

  // 當繪製圖層完成後需要將圖層置入leaflet
  mymap.on(L.Draw.Event.CREATED, function (e) {
     var type = e.layerType,
         layer = e.layer;

     // 可以在此處將圖層座標寫入DB
     mymap.addLayer(layer);
  });

  // 設定 activity 高度
  var height = $(window).height() - 205;
  $("#activity").css('max-height', height);
  $("#activity").css('min-height', height);

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
    $("#bottom-tool").css('display', 'block');
    $("#bottom-tool").stop(true).animate({height: selection_height, opacity: '1'}, 'slow', function(){
    });
  });

});
