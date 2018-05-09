<template>
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col p-0">
	            <div id="mapid" style="height:200px; width:100%;"></div>
	            <!-- <div id="map" style="height:200px; width:100%;"></div> -->
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
				var drawnItems = L.featureGroup().addTo(mymap);

				mymap.addControl(new L.Control.Draw({
		            edit: {
		                featureGroup: drawnItems,
		                poly : {
		                    allowIntersection : false
		                }
		            },
		            draw: {
		                polygon : {
		                    allowIntersection: false,
		                    showArea:true
		                }
		            },
		            position: 'bottomright',
		        }));

		        // Object created - bind popup to layer, add to feature group
		        mymap.on(L.Draw.Event.CREATED, function(event) {
		            var layer = event.layer;
		            
		            var markForm = L.DomUtil.create('div', 'row');
        			markForm.innerHTML = `<div class="form-group">
						<textarea rows="5" class="form-control" id="mark-body"></textarea>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="mark-file">
							<label class="custom-file-label">Choose file</label>
						</div>
						<button id="mark-btn" class="btn btn-sm btn-outline-success">save</button>
					</div>`;

		            layer.bindPopup(markForm)

		            drawnItems.addLayer(layer);

		            var LatLngs = JSON.stringify(layer.toGeoJSON());

				    $("#mark-btn", markForm).click({LatLngs: LatLngs}, function (e) {
				    	let  file = document.getElementById('mark-file').files[0];
				    	let reader = new FileReader()
				    	reader.readAsDataURL(file)

				    	let data = new FormData()
			    		data.append('file_path', file)
			    		data.append('body', $("#mark-body").val())
			    		data.append('l_object', LatLngs)
			    		data.append('lat', 1)
			    		data.append('lng', 1)

				    	let end_point = axios.defaults.baseURL + `/marks/versions/${versionId}`;

					    axios.post(end_point, data)
				        .then(response => {
				        	window.bus.$emit('addMark', response.data.mark)
				        })
				        .catch(error => {
				        	console.log(error)
				        })

				    })

		        });


    		}


    	}
    	
    }
</script>