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

    		this.mymap.on(L.Draw.Event.CREATED, this.drawCreated)
		    this.mymap.on(L.Draw.Event.DELETED, this.drawDeleted)

		    window.bus.$on('updateLeaflet', (markId, body) => {
		    	this.drawnItems.eachLayer((layer) => {
		    		if (layer.markId == markId) {
		    			let form = layer.getPopup().getContent()

		    			$("#mark-body", form).val(body)

		    			layer.openPopup()
		    		}
		    	})
	    	})

	    	window.bus.$on('deleteLeaflet', (markId) => {
	    		this.drawnItems.eachLayer((layer) => {
	    			if (layer.markId == markId) {
		    			this.drawnItems.removeLayer(layer);
		    		}
	    		})
	    	})
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
					draw: {
		            	polyline: false,
		                polygon : {
		                    allowIntersection: false,
		                },
		            },
		            edit: {
		                featureGroup: this.drawnItems,
		                edit: false
		            },
		            position: 'bottomright',
		        }));
    		},
    		drawCreated (event) {
    			var layer = event.layer;
    			// console.log(layer)
    			var LatLngs = JSON.stringify(layer.toGeoJSON());
	            var leafletKey = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

	            layer.leafletKey = leafletKey;

	            let markForm = function () {
		    		let form = L.DomUtil.create('div', 'row');
		    		form.innerHTML = `<div class="form-group">
						<textarea rows="5" class="form-control" id="mark-body"></textarea>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="mark-file">
							<label class="custom-file-label">Choose file</label>
						</div>
						<button id="mark-save-btn" class="btn btn-sm btn-outline-success">save</button>
					</div>`;

					return form;
		    	}

		    	let form = markForm()

	            layer.bindPopup(form, {minWidth: 200})

	            this.drawnItems.addLayer(layer);

	            $("#mark-save-btn", form).on('click', null, { 
	            	layer: layer,
	            	LatLngs: LatLngs,
	            	leafletKey: leafletKey, 
	            	markBody: $("#mark-body", form), 
	            	markFile: $("input:file", form) }, 
	            	this.saveMarkToDB)

	            layer.on('mouseover', () => {
	    			layer.openPopup();
	    		})
    		},
    		drawDeleted (event) {
    			var layers = event.layers;

    			layers.eachLayer(function (layer) {
    				if (!layer.markId) return;

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
    			    		let layer = L.geoJSON(l_object).getLayers()[0]
    			    		layer.markId = mark.id
    			    		layer.leafletKey = mark.leaflet_key
    			    		let form = markForm(mark.body)

    			    		layer._leaflet_id = 1000 + mark.id;

    			    		let replies_endpoint = axios.defaults.baseURL + `/marks/${mark.id}/replies`

    			    		
    			    		axios.get(replies_endpoint)
    			    		    .then(response => {
    			    		    	let replies = '<div style="height: 150px; overflow: auto;">';
    			    				replies += '<div class="col-12"><a href="#">#replies</a></div>';
    			    		    	for(let index=0; index<response.data.replies.length; index++) {
    			    		    		replies = replies + '<div class="col-12">' + response.data.replies[index].body + '</div>';
    			    		    	}
    			    		    	replies += '</div>';
    			    				form.innerHTML = form.innerHTML + replies;
    			    		    })
    			    		    .catch(error => {
    			    		    	console.log(error)
    			    		    })

    			    		// bind it's own data for popup
    			    		// layer.bindPopup(form, {minWidth: 200}).addTo(this.mymap)
    			    		this.drawnItems.addLayer(layer.bindPopup(form, {minWidth: 200}));
    			    		
    			    		$("#mark-save-btn", form).on('click', null, { 
    			    			layer: layer,
    			    			markId: mark.id, 
    			    			markBody: $("#mark-body", form), 
    			    			markFile: $("input:file", form) }, 
    			    			this.updateMarkToDB);

    			    		layer.on('mouseover', () => {
    			    			layer.openPopup();
    			    		})

    			    	})
    			    })
    			    .catch(error => {
    			    	console.log(error)
    			    })
    		},
    		updateMarkToDB (event) {
    			let markId = event.data.markId;
    			let markBody = event.data.markBody.val();
    			let markFile = event.data.markFile.prop('files')[0];

    			let data = new FormData()

		    	if (markFile) {
		    		let reader = new FileReader()
		    		reader.readAsDataURL(markFile)
	    			data.append('file_path', markFile)
		    	}

	    		data.append('body', markBody)
	    		data.append('_method', 'PATCH');

            	let end_point = axios.defaults.baseURL + `/marks/${markId}`;

			    axios.post(end_point, data)
			        .then(response => {
			        	// display the changes on panel
			        	event.data.layer.closePopup()
			        	window.bus.$emit('updateMark', response.data.mark)
			        })
			        .catch(error => {
			        	console.log(error)
			        })
    		},
    		saveMarkToDB (event) {
    			if (event.data.layer.markId) {
    				this.updateMarkToDB({
    					data: {
    						markId: event.data.layer.markId,
    						markBody: event.data.markBody,
    						markFile: event.data.markFile,
    						layer: event.data.layer
    					}
    				})

    				return;
    			}

    			let layer = event.data.layer;
    			let LatLngs = event.data.LatLngs;
    			let leafletKey = event.data.leafletKey;
    			let markBody = event.data.markBody.val();
    			let markFile = event.data.markFile.prop('files')[0];
    			let data = new FormData()

		    	if (markFile) {
		    		let reader = new FileReader()
		    		reader.readAsDataURL(markFile)
	    			data.append('file_path', markFile)
		    	}

	    		data.append('l_object', LatLngs)
	    		data.append('leaflet_key', leafletKey)
	    		data.append('body', markBody)

		    	let end_point = axios.defaults.baseURL + `/marks/versions/${this.version.id}`;
			    axios.post(end_point, data)
			        .then(response => {
			        	layer.markId = response.data.mark.id
			        	layer.closePopup()
			        	// display the new mark on panel
			        	window.bus.$emit('addMark', response.data.mark)
			        })
			        .catch(error => {
			        	console.log(error)
			        })
    		}

    	}
    	
    }
</script>