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
    	data () {
    		return {
    			mymap: null,
    			drawnItems: null,
    		}
    	},
    	mounted () {
    		this.initLeaflet(this.version.id)

    		this.retrieveDraws()

    	},
    	methods: {
    		initLeaflet (versionId) {
    			// 設定leaflet 的高度
				var mapheight = $(window).height() - 45;
				$("#mapid").css('height', mapheight);

				// 初始化 leaflet
				var osmAttrib = 'Vertify Analytics', // 版權說明變數
				osm = L.tileLayer('', {minZoom: 2, maxZoom: 5, center: [0, 0], zoom: 1, attribution: osmAttrib}); // 設定參數

				this.mymap = L.map('mapid', {
				    layers: [osm],
				    center: [0, 0],
				    zoomControl: false
				}).setView([0, 0], 4);

				// 設定圖片大小與座標範圍
				var h = 1280,
				    w = 1920,
				    southWest = this.mymap.unproject([0, h], this.mymap.getMaxZoom()-1),
					northEast = this.mymap.unproject([w, 0], this.mymap.getMaxZoom()-1);
					var bounds = new L.LatLngBounds(southWest, northEast);

			    // 將圖片置入 leaflet
				var url = axios.defaults.baseURL + `/storage/${this.version.image_path}`;
				L.imageOverlay(url, bounds).addTo(this.mymap);

				// 設定最大範圍
				this.mymap.setMaxBounds(bounds);

				// 設定 leaflet-draw 工具列
				this.drawnItems = L.featureGroup().addTo(this.mymap);

				this.mymap.addControl(new L.Control.Draw({
		            edit: {
		                featureGroup: this.drawnItems,
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

		        this.mymap.on(L.Draw.Event.CREATED, this.drawCreated)
		        this.mymap.on(L.Draw.Event.DELETED, this.drawDeleted)

    		},
    		drawCreated (event) {
    			var layer = event.layer;

    			var LatLngs = JSON.stringify(layer.toGeoJSON());
	            var leafletKey = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

	            layer.leafletKey = leafletKey;

	            var markForm = L.DomUtil.create('div', 'row');
    			markForm.innerHTML = `<div class="form-group">
					<textarea rows="5" class="form-control" id="mark-body"></textarea>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="mark-file">
						<label class="custom-file-label">Choose file</label>
					</div>
					<button id="mark-save-btn" class="btn btn-sm btn-outline-success">save</button>
				</div>`;

	            layer.bindPopup(markForm, {minWidth: 200})

	            this.drawnItems.addLayer(layer);

	            var versionId = this.version.id;

	            // 新增一筆mark
	            function saveMark(LatLngs, leafletKey, versionId) {
	            	let data = new FormData()

			    	let  file = document.getElementById('mark-file').files[0];
			    	if (file !== undefined) {
			    		let reader = new FileReader()
			    		reader.readAsDataURL(file)
		    			data.append('file_path', file)
			    	}

		    		data.append('body', $("#mark-body").val())
		    		data.append('l_object', LatLngs)
		    		data.append('leaflet_key', leafletKey)

			    	let end_point = axios.defaults.baseURL + `/marks/versions/${versionId}`;
				    axios.post(end_point, data)
				        .then(response => {
				        	layer.markId = response.data.mark.id
				        	window.bus.$emit('addMark', response.data.mark)
				        })
				        .catch(error => {
				        	console.log(error)
				        })
	            }

	            // 
	            function updateMark(markId) {
	            	let data = new FormData()

			    	let  file = document.getElementById('mark-file').files[0];
			    	if (file !== undefined) {
			    		let reader = new FileReader()
			    		reader.readAsDataURL(file)
		    			data.append('file_path', file)
			    	}

		    		data.append('body', $("#mark-body").val())
		    		data.append('_method', 'PATCH');

	            	let end_point = axios.defaults.baseURL + `/marks/${markId}`;

				    axios.post(end_point, data)
				        .then(response => {
				        	window.bus.$emit('updateMark', response.data.mark)
				        })
				        .catch(error => {
				        	console.log(error)
				        })
	            }

		    	$("#mark-save-btn", markForm).click({
			    	LatLngs: LatLngs,
			    	leafletKey: leafletKey,
			    	versionId: versionId
			    }, function (e) {
			    	layer.closePopup()

			    	if (layer.markId) {
						updateMark(layer.markId)			    			
			    	} else {
			    		saveMark(LatLngs, leafletKey, versionId);
			    	}
			    })

			    // window.bus.$on('updateLeaflet', (markId, body) => {
			    // 	var selector = "textarea[name='" + leafletKey + "']"
			    // 	$(selector, markForm).val('test')
		    	// })
    		},
    		drawDeleted (event) {
    			var layers = event.layers;

    			layers.eachLayer(function (layer) {
					window.bus.$emit('destroyMark', layer.markId, layer.leafletKey)
    			});
    		},
    		retrieveDraws() {
    			let end_point = axios.defaults.baseURL + `/marks/versions/${this.version.id}`
    			
    			let markForm = function (body) {
		    		let form = L.DomUtil.create('div', 'row');
		    		form.innerHTML = `<div class="form-group">
						<textarea rows="5" class="form-control" id="mark-body">${body}</textarea>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="mark-file">
							<label class="custom-file-label">Choose file</label>
						</div>
						<button id="mark-save-btn" class="btn btn-sm btn-outline-success">save</button>
					</div>`;

					return form;
		    	}

    			axios.get(end_point)
    			    .then(response => {
    			    	let marks = response.data

    			    	// retrieve all marks to layer
    			    	marks.forEach(mark => {
    			    		let l_object = JSON.parse(mark.l_object)

    			    		let layer = L.geoJSON(l_object)
    			    		layer.markId = mark.id
    			    		layer.leafletKey = mark.leaflet_key
    			    		let form = markForm(mark.body)

    			    		// bind it's own data for popup
    			    		layer.bindPopup(form, {minWidth: 200}).addTo(this.mymap)
    			    		
    			    		$("#mark-save-btn", form).on('click', null, { 
    			    			markId: mark.id, body: $("#mark-body").val(), file: $("input:file").val() }, 
    			    			this.updateMarkToDB);
    			    	})
    			    })
    			    .catch(error => {
    			    	console.log(error)
    			    })
    		},
    		updateMarkToDB (event) {
    			console.dir(event)
    			// let data = new FormData()

		    	// if (file !== undefined) {
		    	// 	let reader = new FileReader()
		    	// 	reader.readAsDataURL(file)
	    		// 	data.append('file_path', file)
		    	// }

	    		// data.append('body', body)
	    		// data.append('_method', 'PATCH');

       //      	let end_point = axios.defaults.baseURL + `/marks/${markId}`;

			    // axios.post(end_point, data)
			    //     .then(response => {
			    //     	// window.bus.$emit('updateMark', response.data.mark)
			    //     })
			    //     .catch(error => {
			    //     	console.log(error)
			    //     })
    		}

    	}
    	
    }
</script>