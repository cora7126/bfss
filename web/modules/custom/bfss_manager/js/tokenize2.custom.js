jQuery(document).ready(function(){
	
	jQuery('#edit-tags').addClass('tokenize-remote-tags');
	jQuery('.tokenize-remote-tags').tokenize2({
	    dataSource: 'http://5ppsystem.com/get-tags'
	});

	jQuery('#edit-categories').addClass('tokenize-remote-cat');
	jQuery('.tokenize-remote-cat').tokenize2({
	    dataSource: 'http://5ppsystem.com/get-categories'
	});
});