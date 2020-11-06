jQuery.fn.tag = function(options) {

	//default options
	var defaults = {
		seperator : ',',
		unique : true,
		addOnEnter : true,
		style : {
			list : 'taglist',
			item : 'tag',
			input : 'input',
			remove : 'delete'
		}
	};

	//options extends defaults
	options = jQuery.extend(defaults, options);

	jQuery(this).each(function() {

		if ((seperator = jQuery(this).attr('data-seperator')) != '')
			options.seperator = seperator;

		var create_tag = function(text) {
			
			var value = text.toString().replace(/^\s+|\s+$/g, '');
			
			if (value == '')
				return;

			
			var item = jQuery('<li/>').addClass(options.style.item);
			
			var tag = jQuery('<span/>');
			var close_text = jQuery('<span/>').html('[X]');
			var close = jQuery('<a/>', {tabindex:'-1'}).addClass(options.style.remove).append(close_text).click(function() {
				
				jQuery(this).closest('li').remove();
				
				update_input();
			});

			
			if (options.unique && jQuery.inArray(value, values) > -1) return;

			
			values.push(value);
			
			tag.html(value);
			
			item.append(tag).append(' ').append(close);

			
			return item;
		};

		
		var add_tag = function(input) {
			
			if (jQuery(input).val() != '') {
				
				var item = create_tag(jQuery(input).val());
				
				if (!item)
				{
					
					jQuery(input).val('');
					jQuery(input).width(8);
				}
				else {
					
					jQuery(input).closest('li').before(item);
					
					jQuery(input).val(jQuery(input).val().replace(options.seperator, ''));				
					
					jQuery(input).width(8).val('').focus();
				}
				
				update_input();
				
				shadow.html('');
			}
		};

		
		var update_input = function() {
			
			var tags = [];
			
			jQuery('li.'+options.style.item+' > span', list).each(function() {
				
				tags.push(jQuery(this).html());
			});
			values = tags;
			
			jQuery(input).val(tags.join(options.seperator));
		};

		
		var input = jQuery(this);
		
		if (input.is(':input')) {
			
			input.hide();
			
			var list = jQuery('<ul/>').addClass(options.style.list).click(function() {
				jQuery(this).find('input').focus();
			});
			
			var add = jQuery('<input/>', {type: 'text'});
			
			var tags = input.val().split(options.seperator);
			var values = [];
			
			for (index in tags) {
				var item = create_tag(tags[index]);
				list.append(item);
			}
			
			update_input();
			
			input.after(list);
			
			var input_container = jQuery('<li/>').addClass(options.style.input);
			
			var shadow = jQuery('<span/>');
			
			shadow.hide();
			
			input_container.append(add);
			
			add.after(shadow);
			
			list.append(input_container);

			var auto_width = function(input)
			{
				
				shadow.html(jQuery(input).val().replace(/\s/g,'&nbsp;'));
				
				var zone = (jQuery(input).val() == ''?8:10);
				
				jQuery(input).width(shadow.width() + zone);
			};

			//onkeyup'da yalnizca width'i ayarla
			add.bind('keyup',function(){
				auto_width(this);
			})
			//onkeydown implementasyonu
			.bind('keydown', function(event) {
				//width
				auto_width(this);
				var key = event.keyCode || event.which;
				
				//eger bossa ve backspace'e basilmissa
				if (jQuery(this).val() == '' && (key == 8 || key==46)) //backspace or delete
				{
					//genislik ayarla
					jQuery(this).width(jQuery(this).val()!=''?shadow.width()+5:8);
					//inputun li'sinden bi onceki veya bi sonraki 'li'yi sil.
					switch (key)
					{
						//eger backspace ise onceki sil
						case 8: 
							if (jQuery(this).closest('li').prev().is('.ready-to-delete')) {
								jQuery('.ready-to-delete').removeClass('ready-to-delete');
								jQuery(this).closest('li').prev().remove();
							} else {
								jQuery('.ready-to-delete').removeClass('ready-to-delete');
								jQuery(this).closest('li').prev().addClass('ready-to-delete');
							}
						break;
						//eger delete ise sonraki sil
						case 46: 
							if (jQuery(this).closest('li').next().is('.ready-to-delete')) {
								jQuery('.ready-to-delete').removeClass('ready-to-delete');
								jQuery(this).closest('li').next().remove();
							} else {
								jQuery('.ready-to-delete').removeClass('ready-to-delete');
								jQuery(this).closest('li').next().addClass('ready-to-delete');
							}
						break;
					}
					
					//degerleri duzenle
					update_input();

					//false.
					event.preventDefault();
					return false;
				} else {
					jQuery('.ready-to-delete').removeClass('ready-to-delete');
				}

				//eger deger bossa
				if (jQuery(this).val() == '')
				{
					//yukari veya sola basilmissa
					if (key == 37 || key == 38) //left, up
					{
						//input li'sini yukari tasi
						jQuery(this).width(jQuery(this).val()!=''?shadow.width()+5:8);
						jQuery(this).closest('li').prev().before(jQuery(this).closest('li'));
						jQuery(this).focus();
					}

					//asagi veya saga basilmissa
					if (key == 39 || key == 40) //down, right
					{
						//input li'sini asagi tasi
						jQuery(this).width(jQuery(this).val()!=''?shadow.width()+5:8);
						jQuery(this).closest('li').next().after(jQuery(this).closest('li'));
						jQuery(this).focus();
					}
				}

			})
			//keypress'te
			.bind('keypress', function(event) {
				auto_width(this);
				var key = event.keyCode || event.which;
				//eger basilan tus seperator ise, veya addonenter option aciksa ve entere basilmissa
				if (options.seperator == String.fromCharCode(key) || options.seperator == key || (options.addOnEnter && key == 13)) {
					//tag ekle
					add_tag(this);
					//false
					event.preventDefault();
					return false;
				}
			})
			//blur oldugunda
			.bind('blur', function() {
				//eger tag yazildiysa ekle
				add_tag(this);
				//input ortalarda falansa diye, en sona atalim onu
				jQuery(this).closest('ul').append(jQuery(this).closest('li'));
			});
		}

	});

};