$(function() {
	// Load jqte editor
	$(".jqte").jqte();
});


// Tree init
$(function(){

	$("#tree").fancytree({
		clickFolderMode: 3,
		extensions: ["dnd", "persist"],
      	persist: {
        	expandLazy: true,
        	overrideSource: false, // true: cookie takes precedence over `source` data attributes.
        	store: "auto" // 'cookie', 'local': use localStore, 'session': sessionStore
      	},
		source:{
			url: "/navigation/getTree",
			cache: false,
			data: {
				op: 'json'
			}
		}, 
		activate: function(event, data) {
			console.log("click:" + data.node.key);
			_url = '/admin/editnav/' + data.node.key;
			if (self.location.pathname != _url) {
				self.location = _url;
			}
		},
		onPostInit: function() {
			// Expand the domain folder
			var tree = $("#tree").dynatree("getTree");
			var node = tree.getNodeByKey("folder-0");
			node.toggleExpand();
		},
		dnd: {
			autoExpandMS: 400,
			preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			
			dragEnter: function(node, sourceNode) {
				$.ui.fancytree.debug("tree.onDragEnter(%o, %o)", node, sourceNode);
				return true;
			},
			
			onDragOver: function(node, sourceNode, hitMode) {
				$.ui.fancytree.debug("tree.onDragOver(%o, %o, %o)", node, sourceNode, hitMode);
			},
			onDragLeave: function(node, sourceNode) {
				$.ui.fancytree.debug("tree.onDragLeave(%o, %o)", node, sourceNode);
			},
			
			dragDrop: function(node, data) {
				
				id = $(data.draggable.element).attr("image_id");
				image_checkbox = "#image_checkbox_" + id;
				$(image_checkbox).prop('checked', true);
				
				category_id = data.node.key.substring(7);
				
				$('.images_checkbox').each(function(){
					if ($(this).is(":checked")){
						file_id = $(this).attr("file_id");
						_url  = "/modules/wsImagemanager/admin/files.php?op=change_category&file="+file_id+"&category="+data.node.key;
						console.log("Calling: " + _url);

						$.ajax({
						 	dataType: "json",
						  	url: _url,
						}).done(function(response) {
							console.log(response);
							if (response.status == "error"){
								$.gritter.add({
									position: 'top-right',
									title: 'Error!',
									text: response.message
								});
							}
							if (response.status == "ok"){
								window.deletedFilesCount++;
								$.gritter.add({
									position: 'top-right',
									title: $('#_MD_FILER_MSG_SUCCESS').text(),
									text: response.message
								});
							}
						});
					}
				});
				
				node.setActive(true);
				node.setExpanded(true);
				category_id = data.node.key.substring(7);
				files_url = "/modules/wsImagemanager/admin/files.php?category_id=" + category_id;
				uploader.settings.url='/modules/wsImagemanager/admin/upload.php?category_id=' + category_id;
				window.files_url = files_url;
				if (category_id > 0 ){
					$("#files").load(window.files_url);
				}
				
				
				return;
				
		
				_url  = "/modules/wsImagemanager/admin/files.php?op=change_category&file="+id+"&category="+data.node.key;
				$.ajax({
					url: _url,
				}).done(function() {
					node.setActive(true);
					node.setExpanded(true);
					category_id = data.node.key.substring(7);
					files_url = "/modules/wsImagemanager/admin/files.php?category_id=" + category_id;
					uploader.settings.url='/modules/wsImagemanager/admin/upload.php?category_id=' + category_id;
					window.files_url = files_url;
					if (category_id > 0 ){
						$("#files").load(window.files_url);
					}
				});
			}
		}
	});
});
