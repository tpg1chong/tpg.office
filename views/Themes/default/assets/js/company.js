var DataLists = {
	init: function (settings, elem) {
		var self = this;

		self.$elem = $( elem );
		self.$listsbox = self.$elem.find('[data-ref=listsbox]');
		self.$content = self.$elem.find('[data-ref=content]');

		self.settings = $.extend( {}, {
			options: {
				pager: 1
			}
		}, settings );

		self.data = {
			total: 0,
			options: self.settings.options,
		};

		self.currKeyword = $.trim( self.$elem.find('[data-action=search]').find(':input#keywords').val() );

		self.currActionTab = self.$elem.find('.active[data-action-tab]').attr('data-action-tab');
		if( !self.currActionTab ){
			self.currActionTab = self.$elem.find('[data-action-tab]').first().attr('data-action-tab');
		}
		

		self.refresh();
		self.Events();
	},

	Events: function () {

		var self = this;
		self.$elem.find('[data-action=refresh]').click(function (e) {

			self.refresh( 200 );
			e.preventDefault();
		});

		self.$elem.find(':input[data-filter]').change(function() {
			
			
			self.clearData();
			self.refresh( 200 );
		});

		self.$listsbox.parent().scroll(function( e ) {
			
			
			if( ((self.$listsbox.outerHeight() - $(this).scrollTop()) <= ($(this).outerHeight()+300)) && !self.$listsbox.parent().hasClass('has-loading') && self.data.options.more ){

				self.data.options.pager = parseInt(self.data.options.pager);
				self.data.options.pager += 1;

				self.refresh();
			}
		});


		self.$listsbox.delegate('[data-id]', 'click', function(event) {
			
			self.active( $(this).data() );
		});


		self.$elem.find('[data-action-tab]').click(function(event) {
			
			if( $(this).hasClass('active') ) return false;
			self.currActionTab = $(this).attr('data-action-tab');
			$(this).addClass('active').siblings().removeClass('active');
			self.actionTab();
		});

		self.$elem.find('[data-action=search]').submit(function(event) {
			event.preventDefault();

			self.clearData();

			self.currKeyword = $.trim( self.$elem.find('[data-action=search]').find(':input#keywords').val() );
			self.data.options.q = self.currKeyword;
			self.refresh();
		});


		self.$elem.find('[data-action=search] :input#keywords').keyup(function() {
			
			var val = $.trim( $(this).val() );
			if( self.currKeyword!=val && val=='' ){

				self.currKeyword = '';
				self.clearData();
				self.refresh();
			}
		});

		self.$elem.find('[data-action-profile=delete]').click(function() {
			if( !self.currItem ) return false;
			var href = $(this).data('href');

			Dialog.load( href + self.currItem.id, {
				callback: 1
			}, {
				onClose: function () {
					console.log('onClose');
				},
				onSubmit: function ( $d ) {

					$form = $d.$pop.find('form');

					Event.inlineSubmit( $form ).done(function( result ) {

						result.url = '';
						Event.processForm($form, result);

						self.delProfile();
					});

					
				}
			} );
		});
		

		/* -- Contact -- */
		self.$elem.delegate('[data-action-contact=search]', 'submit', function(event) {
			event.preventDefault();

		});
		self.$elem.delegate('[data-action-contact=add], [data-action-contact=edit]', 'click', function(event) {
			event.preventDefault();
			
			if( !self.currItem ) return false;
			var href = $(this).data('href');

			var dataPost = {
				callback: 1,
				companyId: self.currItem.id,
			};



			Dialog.load( href, dataPost, {
				onClose: function () {
					console.log('onClose');
				},
				onSubmit: function ( $d ) {

					$form = $d.$pop.find('form');
					console.log( 'onSubmit' );
					/*Event.inlineSubmit( $form ).done(function( result ) {

						result.url = '';
						Event.processForm($form, result);

						self.delProfile();
					});*/
				}
			});
		});

	},


	clearData: function () {
		var self = this;

		self.$listsbox.parent().removeClass('has-data');
		self.$listsbox.empty();
		self.data.options.pager = 1;


		if( self.data.options.q ){
			delete self.data.options.q;
		}
	},
	

	refresh: function (length)  {
		var self = this;

		// if( self.$listsbox.parent().hasClass('has-loading') ) return false;

		if( self.is_loading ) clearTimeout( self.is_loading );
		/*if( self.$listsbox.parent().hasClass('has-error') ){
			self.$listsbox.parent();
		}*/
		// self.$listsbox.parent();

		self.$listsbox.parent()
			.removeClass('has-error')
			.removeClass('has-empty')
			.addClass('has-loading');

		// if( !self.settings.url ) return false;
		self.is_loading = setTimeout(function () {
			self.fetch().done(function( results ) {

				self.data.options = $.extend( {}, self.data.options, results.options );
				if( results.error || results.total == 0 ){

					if( results.total == 0 ){
						self.$listsbox.parent().addClass('has-empty');
					}
					else{

					}

					return false; 
				}
				console.log( self.data.options );

				if( !self.$listsbox.parent().hasClass('has-data') ){
					self.$listsbox.parent().addClass('has-data');
				}

				self.$elem.find('[data-text=total]').text( PHP.number_format(results.total) ).parent().show();

				self.buildFrag(results.items);
				
			});
		}, length || 1500);
	},

	fetch: function() {
		var self = this;

		if( self.is_search ) self.$elem.find('.search-input').attr('disabled', true);

		$.each(self.$elem.find(':input[data-filter]'), function(index, el) {
			
			var name = $(this).attr('data-filter'),
				val = $(this).val();
			if(name=='sort'){

				self.data.options.dir = val=="company.name" ? 'ASC':"DESC";
			}
			self.data.options[ name ] = val;
		});
		// console.log( self.data.options );


		return $.ajax({
			url: self.settings.url,
			data: self.data.options,
			dataType: 'json'
		}).always(function () {

			self.$listsbox.parent().removeClass('has-loading');

			if( self.is_search ){
				self.$elem.find('.search-input').attr('disabled', false);
				self.$elem.find('.search-input').focus();

				self.is_search = false;
			}
			
		}).fail(function() { 
			self.$listsbox.parent().addClass('has-error');
		});
	},

	buildFrag: function (results) {
		var self = this;


		$.each(results, function(index, obj) {
			self.$listsbox.append( self.display(obj) );
		});
	},
	display: function (data) {
		var self = this;

		var $item = $('<div>', {'data-id': data.id, class: "item"});	
		

		$item.append( $('<div>', {class: 'itemAvatar'}) );
		$item.append( $('<div>', {class: 'itemContent'}).append(
			  $('<div>', {class: 'title fwb'}).text( data.name )
			, ( data.business_name ? $('<div>', {class: 'category fss fcg'}).text( data.business_name ): '' )
			, $('<div>', {class: 'text fss'}).append(

				  $('<span>').append( 'Expats: ', data.expatTotal ? data.expatTotal: '-' )
				, $('<span>', {class: 'mhm fcg'}).text( '|' )
				, $('<span>').append( 
					$('<i>', {class: 'icon-address-book-o mrs'}) 
					, data.contactTotal==0 ? '-': data.contactTotal
				) 
				, $('<span>', {class: 'mhm fcg'}).text( '|' )
				, $('<span>').append( 
					$('<i>', {class: 'icon-user-circle-o mrs'}) 
					, data.clientTotal==0 ? '-': data.clientTotal
				) 

			)
			// , $('<div>', {class: 'topR'}).append( 'Expats: ', data.expatTotal ? data.expatTotal: '-' )
			, $('<div>', {class: 'time'}).append( data.created_str )
		) );

		$item.data( data );

		return $item;
	},



	active: function (data) {
		var self = this;

		var $item = self.$listsbox.find('[data-id='+ data.id +']');
		if( $item.hasClass('active') ) return false;

		$item.addClass('active').siblings().removeClass('active');

		self.currItem = data;
		self.setProfile();

		self.actionTab();
	},

	setProfile: function () {
		var self = this;

		var $content = self.$elem.find('[role=content]');
		$content.addClass('has-loading');

		// setTimeout(function () {
			
			$content.removeClass('has-empty').removeClass('has-loading');
			$.each(self.currItem, function(index, val) {
				$el = $content.find('[data-profile='+ index +']');

				$el.parent().toggleClass('hidden_elem', !val || val=='' || val==0);

				if( $el.length && val ){

					console.log( val );

					var tagName = $el.prop("tagName");
					if( tagName=='' ){

					}
					else{
						$el.html( val );
					}
				}
			});

			console.log( self.currActionTab, self.currItem );

		// }, 800);
		
	},
	delProfile: function () {
		var self = this;

		var $content = self.$elem.find('[role=content]');
		$content.addClass('has-empty');
		
		self.$listsbox.find('[data-id='+self.currItem.id+']').remove();

		self.currItem = null;
	},

	actionTab: function () {
		var self = this;

		if( !self.currItem ) return false;


		self.$content.parent().addClass('has-loading');
		self.$elem.find('[role=main]').scrollTop(0);
		$.get( Event.URL + 'companies/getTab/' + self.currActionTab, {id: self.currItem.id}, function ( res ) {

			self.$content.parent().removeClass('has-loading');
			var $html = $(res);
			self.$content.html( $html );


			Event.plugins( $html );
		});
	}
}


$(function () {
	
	DataLists.init( {
		url: Event.URL + 'companies/search'
	}, $('[data-ref=forumlists]') );

});
