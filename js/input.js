(function($){
	
	
	function initialize_field( $el ) {
		var select = $('select', $el);
		var imgPreviewContainer = $('.acf-gallery-attachments', $el);
		select.on('change', function(){
			var requestUrl = $('option:selected', select).data('gallery-url');
			$.ajax(requestUrl).done(function(result){
				var html = "";
				for(var i = 0; i < result.data.length; i++) {
					var img = result.data[i];
					var nodeHtml = '<div class="acf-gallery-attachment acf-soh" data-id="' + img.id + '">' +
								'<div class="margin" title="">'+
									'<div class="thumbnail">'+
										'<img src="' + img.source + '" />'+
									'</div>'+
								'</div>'+
							'</div>';
					html += nodeHtml;
				}
				imgPreviewContainer.html(html);
			});
		});
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'facebook_gallery'
			acf.get_fields({ type : 'facebook_gallery'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="facebook_gallery"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);
