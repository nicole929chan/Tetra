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
    		this.initLeaflet ()
    	},
    	methods: {
    		initLeaflet () {
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



    		}
    	}
    }
</script>