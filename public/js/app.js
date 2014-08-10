$(function() {
		$("button,input[type=submit]").addClass("button");
		
		$('#newAlbum').on("click", function(){
			var name = prompt("Please enter name of the new album", "");
			self.location  = "/index/newAlbum?name=" + escape(name);
		})

});



// Initialize the widget when the DOM is ready
$(function() {
	_upload_url = '/upload.php';
	if (window.currentAlbum != "undefined"){
		_upload_url += '?currentAlbum=' + window.currentAlbum;
	}
	
	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : _upload_url,

		complete : function(){
			var _newURL = self.location;
			
			if (_newURL.href.indexOf('?') == -1){
				_newURL +=  '?refresh=1';
			} else {
				_newURL += '&refresh=1';
			}
			
			self.location = _newURL;

		},
		

		// User can upload no more then 200 files in one go (sets multiple_queues to false)
		max_file_count: 200,

		chunk_size: '1mb',

		// Resize images on clientside if we can
		resize : {
			width : 800, 
			height : 600, 
			quality : 70,
			crop: true // crop to exact dimensions
		},

		filters : {
			// Maximum file size
			max_file_size : '1000mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png,jpeg,pjpeg"}
			]
		},

		// Rename files by clicking on their titles
		rename: true,

		// Sort files
		sortable: true,

		// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
		dragdrop: true,

		// Views to activate
		views: {
			list: true,
			thumbs: true, // Show thumbs
			active: 'thumbs'
		},

		// Flash settings
		flash_swf_url : '/js/bower_components/plupload/js/Moxie.swf',

		// Silverlight settings
		silverlight_xap_url : '/js/bower_components/plupload/js/Moxie.xap'
	});


	// Handle the case when form was submitted before uploading has finished
	$('#form').submit(function(e) {
		// Files in queue upload them first
		if ($('#uploader').plupload('getFiles').length > 0) {

			// When all files are uploaded submit form
			$('#uploader').on('complete', function() {
				$('#form')[0].submit();
			});

			$('#uploader').plupload('start');
		} else {
			alert("You must have at least one file in the queue.");
		}
		return false; // Keep the form from submitting
	});
});
