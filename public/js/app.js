$(function() {
	// Load jqte editor
	$(".jqte").jqte();
});


// Tree init
$(function(){

	$("#tree").fancytree({
		activeVisible: true,
		clickFolderMode: 3,
		xextensions: ["xdnd", "xpersist"],
      	persist: {
        	expandLazy: false,
        	overrideSource: true, // true: cookie takes precedence over `source` data attributes.
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
			console.log(self.location);
			_url = '/admin/editnav/' + data.node.key;
			if (self.location.pathname != _url) {
				self.location = _url;
			}
		}
	});


	$("#sitemenu").fancytree({
		clickFolderMode: 3,
		source:{
			url: "/navigation/getTree",
			cache: true,
			data: {
				op: 'json'
			}
		}, 
		activate: function(event, data) {
			console.log("click:" + data.node.key);
			_url = '/navigation/show/' + data.node.key;
			if (self.location.pathname != _url) {
				self.location = _url;
			}
		}
	});

});
