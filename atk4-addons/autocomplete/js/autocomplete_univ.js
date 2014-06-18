$.each({
	// Useful info about mouse clicking bug in jQuery UI:
	// http://stackoverflow.com/questions/6300683/jquery-ui-autocomplete-value-after-mouse-click-is-old-value
	// http://stackoverflow.com/questions/7315556/jquery-ui-autocomplete-select-event-not-working-with-mouse-click
	// http://jqueryui.com/demos/autocomplete/#events (check focus and select events)

	myautocomplete: function(data, other_field, options, id_field, title_field){

		var q=this.jquery;

		this.jquery.autocomplete($.extend({
			source: data,
			focus: function( event, ui ) {
				// Imants: fix for item selecting with mouse click
				var e=event;
				while(e.originalEvent!==undefined) e=e.originalEvent;
				if(e.type!='focus') q.val( ui.item[title_field] );

				return false;
			},
			select: function( event, ui ) {
				q.val( ui.item[title_field] );
				$(other_field).val( ui.item[id_field] );
				
				return false;
			},
			change: function(event, ui) {
				var data=$.data(this);//Get plugin data for 'this'
				if(data.uiAutocomplete.selectedItem==undefined) {
					if("mustMatch" in options) q.val('');
					$(other_field).val(q.val());
					
					return false;
				}
			}
		},options))
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "ui-autocomplete-item", item )
				.append( "<a>" + item[title_field] + "</a>" )
				.appendTo( ul );
		};

	}

},$.univ._import);
