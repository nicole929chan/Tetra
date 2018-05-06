<template>
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col p-0">
	            <div id="mapid" style="height:200px; width:100%;"></div>
	        </div>
	    </div>
	</div>
</template>

<script>
    require('leaflet');
	require('leaflet-draw');

    export default {
    	props: ['version'],
    	mounted () {
    		this.initLeaflet (this.version.id)
    	},
    	methods: {
    		initLeaflet (versionId) {
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
				var url = axios.defaults.baseURL + `/storage/${this.version.image_path}`;
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
			    	console.log(e)
				    var type = e.layerType, layer = e.layer;

				    var LatLngs = JSON.stringify(layer.toGeoJSON());

				    mymap.addLayer(layer);

				    // let end_point = axios.defaults.baseURL + `/marks/versions/${versionId}`;

				    // axios.post(end_point, {
				    // 	    body: 'test',
				    // 	    l_object: LatLngs,
				    // 	    lat: 1,
				    // 	    lng: 1
				    //     })
				    //     .then(response => {
				    //     	console.log(response)
				    //     })
				    //     .catch(error => {
				    //     	console.log(error)
				    //     })
				    
				    let form = '<form id="form-mark" name="form-mark">'
				    form += '<textarea name="body" rows="5"></textarea>'
				    form += '<div><button name="submit">submit</button></div>'
				    form += '</form>'

				    // form += '<div><button>a button</button></div>'
				    
				    layer.bindPopup(form);

				});

				


    		}


    	}
    }
</script>