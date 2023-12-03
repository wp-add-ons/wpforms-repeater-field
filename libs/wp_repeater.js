(function($) {
"use strict";
	jQuery(document).ready(function($){
		get_repeater_data_name();
		function change_name_and_ids(item , field_end = null, id_rand = null){
			var datas = JSON.parse(field_end.find(".wpforms-field-repeater-data").val());
			var datas_ids = datas.id;
			datas_ids.push(id_rand);
			datas.id = datas_ids;
			field_end.find(".wpforms-field-repeater-data").val(JSON.stringify(datas));	
			item = $(item);
			var formID = field_end.closest("form").data("formid");
			item.find(".wpforms-field",).each(function(){ 
				var field_id = $(this).data("field-id");
				$(this).attr("data-field-id", field_id + "-" + id_rand);
				var id = $(this).attr("id").replace("field_"+field_id+"-container", "field_"+field_id+ "-" + id_rand +"-container");
				$(this).attr("id", id);
				update_wpforms_conditional_logic(formID,field_id,id_rand);
			})
			item.attr("data-id",id_rand);
			$("input",item).each(function(){
				var name = $(this).attr("name");
				var type = $(this).attr("type");
				var id = $(this).attr("id");
				var classs = $(this).attr("class");
				var index = $(this).closest(".container-repeater-field").data("step");
				name = name.replace(/\[([0-9]+)\]/,"[$1"+"_"+id_rand+"]");
				$(this).attr("name",name);
				$(this).attr("id",id+"-"+id_rand);
				//for radio
				if($(this).closest("li").find("label") ){
					$(this).closest("li").find("label").attr("for",id+"-"+id_rand);
				}
			})
			$("textarea",item).each(function(){
				var name = $(this).attr("name");
				var id = $(this).attr("id");
				name = name.replace(/\[([0-9]+)\]/,"[$1"+"_"+id_rand+"]");
				$(this).attr("name",name);
				$(this).attr("id",id+"-"+id_rand+"");
			})
			$("select",item).each(function(){
				var name = $(this).attr("name");
				var id = $(this).attr("id");
				name = name.replace(/\[([0-9]+)\]/,"[$1"+"_"+id_rand+"]");
				$(this).attr("name",name);
				$(this).attr("id",id+"-"+id_rand+"");
			})
			return item;
		}
		function add_repeater_data(button){
			var start_field;
			var id_rand = Math.floor(Math.random() * 10000);
			var item = $('<div class="repeater-field-item"><div class="repeater-field-header"></div><div class="repeater-field-content"></div></div>');
			button.prevAll().each(function(index){ 
				var item = $(this).clone();
				if( item.hasClass("wpforms-field wpforms-field-repeater_start") ) {
					start_field = $(this);
					return false;
				}
			})
			var html_field = get_repeater_data(button,id_rand);
			var header = get_repeater_data_header(start_field);
			item.find(".repeater-field-header").append(header);
			item.find(".repeater-field-content").append(html_field);
			button.find(".repeater-field-warp-item").append(item);
			update_repeater_count_header();
			item.find("input,select,textarea").change();
			$( document ).trigger( "done_load_repeater" );
			Superaddons_loadDatePicker();
			Superaddons_loadTimePicker();
		}
		function get_repeater_data(step_field,id_rand){
			var html_step = change_name_and_ids(step_field.find(".wpforms-field-repeater-data-html").val(),step_field,id_rand);
			console.log(html_step);
			return html_step;
		}
		function get_repeater_data_name(){
			var i = 1;
			$(".wpforms-field-repeater_start").each(function(){
				var html_step = $("<div class='container-repeater-field'></div>");
				var names = [];
				var step_field = "";
				var elements = $(this).nextAll();
				elements.each(function(index){ 
						var item = $(this).clone();
						if( item.hasClass("wpforms-field-repeater") ) {
							$(this).attr("data-id",i);
							step_field = $(this);
							$(this).find(".wpforms-field-repeater-data").val(JSON.stringify({"count":1,"fields":names,"id":[]}));
							return false;
						}
						$(this).remove();
						html_step.append(item);
						var check_name = null;
						if( item.find("input").attr("name") ) {
							names.push(item.find("input").attr("name"));
						}else if( item.find("textarea").attr("name") ){
							names.push(item.find("textarea").attr("name"));
						}else if( item.find("select").attr("name") ){
							names.push(item.find("select").attr("name"));
						}else{
							names.push(item.find("input").attr("name"));
						}	
				})
				var text_html = "<div class='container-repeater-field'>"+html_step.html()+"</div>";
				step_field.find(".wpforms-field-repeater-data-html").val(text_html);
				var initial_rows = 0;
				initial_rows = step_field.find(".repeater-field-warp-item-data").data("initial_rows");
				setTimeout(function() {
				  for (var j = 0; j < initial_rows; j++) {
					add_repeater_data(step_field.closest(".wpforms-field-repeater"));
				}
				}, 100);
				i++;
			})
		}
		function get_repeater_data_header(start_field){
			var html_step = start_field.find(".repeater-field-header-data").val();
			return html_step;
		}
		function update_repeater_count_header(){
			$(".wpforms-field-repeater").each(function(){
					var i = 1;
					$(".repeater-field-item",$(this)).each(function(){
						$(this).find(".repeater-field-header-count").html(i);
						i++;
					})
					var datas = JSON.parse($(this).find(".wpforms-field-repeater-data").val());
					datas.count = i-1;
					$(this).find(".wpforms-field-repeater-data").val(JSON.stringify(datas));
			});
		}
		function check_max_row(step_field){
			var max = step_field.find(".repeater-field-warp-item-data").data("limit");
			var number_item = $('.repeater-field-item',step_field).length;
			if( number_item >= max ){
				return false;
			}else{
				return true;
			}
		}
		function check_min_row(step_field){
			var min = step_field.find(".repeater-field-warp-item-data").data("initial_rows");
			var number_item = $('.repeater-field-item',step_field).length;
			if( number_item <= min ){
				return false;
			}else{
				return true;
			}
		}
		function removeAR(arr) {
		    var what, a = arguments, L = a.length, ax;
		    while (L > 1 && arr.length) {
		        what = a[--L];
		        while ((ax= arr.indexOf(what)) !== -1) {
		            arr.splice(ax, 1);
		        }
		    }
		    return arr;
		}
	    $("body").on("click",".wpforms-repeater-field-button-add",function(e){
	    	e.preventDefault();
	    	if( check_max_row($(this).closest(".wpforms-field-repeater")) ){
	    		add_repeater_data($(this).closest(".wpforms-field-repeater"));
	    	}else{
	    		$(this).addClass('hidden');
	    	}
	    })
	    $("body").on("click",".repeater-field-header-acctions-toogle",function(e){
	    	e.preventDefault();
	    	if( $(this).hasClass("icon-down-open")){
	    		$(this).removeClass("icon-down-open");
	    		$(this).addClass("icon-up-open");
	    	}else{
	    		$(this).addClass("icon-down-open");
	    		$(this).removeClass("icon-up-open");
	    	}
	    	$(this).closest(".repeater-field-item").find(".repeater-field-content").slideToggle("slow");
	    	$(this).closest(".repeater-field-item").find(".repeater-field-header").toggleClass('repeater-content-show');
	    })
	    $("body").on("click",".repeater-field-header-acctions-remove",function(e){
	    	e.preventDefault();
	    	$(this).closest(".wpforms-field-repeater").find(".wpforms-repeater-field-button-add").removeClass('hidden');
	    	if( check_min_row($(this).closest(".wpforms-field-repeater")) ){
	    		var id = $(this).closest(".repeater-field-item").find(".container-repeater-field").data("id");
	    		var datas = JSON.parse($(this).closest(".wpforms-field-repeater").find(".wpforms-field-repeater-data").val());
				var datas_ids = datas.id;
				datas_ids = removeAR(datas_ids,id);
				datas.id = datas_ids;
				$(this).closest(".wpforms-field-repeater").find(".wpforms-field-repeater-data").val(JSON.stringify(datas));
	    		$(this).closest(".repeater-field-item").remove();
	    	}else{
	    	}
	    	update_repeater_count_header();
	    })
	    function update_wpforms_conditional_logic(formid,id = 0, rand_key = 0){
	    	if ( typeof wpforms_conditional_logic === 'undefined' || typeof wpforms_conditional_logic[formid] === 'undefined' ) {
				return false;
			}
	    	var wpforms_conditional_logic_id = wpforms_conditional_logic[formid];
	    	wpforms_conditional_logic[formid] = wpforms_conditional_logic_id;
	    	if (typeof wpforms_conditional_logic_id[id]!=='undefined') {
	    		var logic = [];
	    		$.each( wpforms_conditional_logic_id[id].logic, function( keys, values ) {
	    			var data_value = [];
				  	$.each( values, function( key, value ) {
				  		data_value[key] = {"field": value.field + "-"+rand_key, "operator": value.operator, "type": value.type, "value": value.value}
					});
					logic[keys] =data_value;
				});
	    		wpforms_conditional_logic_id[id+'-'+rand_key] = {"logic":logic,"action":wpforms_conditional_logic_id[id].action };
	    	}
	    	
	    }
	     function Superaddons_loadDatePicker() {
			if ( typeof $.fn.flatpickr !== 'undefined' ) {
				$( '.wpforms-datepicker-wrap' ).each( function() {

					var element = $( this ),
						$input  = element.find( 'input' ),
						form    = element.closest( '.wpforms-form' ),
						formID  = form.data( 'formid' ),
						fieldID = element.closest( '.wpforms-field' ).data( 'field-id' ),
						properties;

					if ( typeof window['wpforms_' + formID + '_' + fieldID] !== 'undefined' && window['wpforms_' + formID + '_' + fieldID].hasOwnProperty( 'datepicker' ) ) {
						properties = window['wpforms_' + formID + '_' + fieldID].datepicker;
					} else if ( typeof window['wpforms_' + formID] !== 'undefined' && window['wpforms_' + formID].hasOwnProperty( 'datepicker' ) ) {
						properties = window['wpforms_' + formID].datepicker;
					} else if ( typeof wpforms_datepicker !== 'undefined' ) {
						properties = wpforms_datepicker;
					} else {
						properties = {
							disableMobile: true,
						};
					}

					// Redefine locale only if user doesn't do that manually, and we have the locale.
					if (
						! properties.hasOwnProperty( 'locale' ) &&
						typeof wpforms_settings !== 'undefined' &&
						wpforms_settings.hasOwnProperty( 'locale' )
					) {
						properties.locale = wpforms_settings.locale;
					}

					properties.wrap = true;
					properties.dateFormat = $input.data( 'date-format' );
					if ( $input.data( 'disable-past-dates' ) === 1 ) {
						properties.minDate = 'today';
					}

					var limitDays = $input.data( 'limit-days' ),
						weekDays = [ 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ];

					if ( limitDays && limitDays !== '' ) {
						limitDays = limitDays.split( ',' );

						properties.disable = [ function( date ) {

							var limitDay;
							for ( var i in limitDays ) {
								limitDay = weekDays.indexOf( limitDays[ i ] );
								if ( limitDay === date.getDay() ) {
									return false;
								}
							}

							return true;
						} ];
					}

					// Toggle clear date icon.
					properties.onChange = function( selectedDates, dateStr, instance ) {

						var display = dateStr === '' ? 'none' : 'block';
						element.find( '.wpforms-datepicker-clear' ).css( 'display', display );
					};

					element.flatpickr( properties );
				} );
			}
		}
		 function Superaddons_loadTimePicker() {

			// Only load if jQuery timepicker library exists.
			if ( typeof $.fn.timepicker !== 'undefined' ) {
				$( '.wpforms-timepicker' ).each( function() {
					var element = $( this ),
						form    = element.closest( '.wpforms-form' ),
						formID  = form.data( 'formid' ),
						fieldID = element.closest( '.wpforms-field' ).data( 'field-id' ),
						properties;

					if (
						typeof window['wpforms_' + formID + '_' + fieldID] !== 'undefined' &&
						window['wpforms_' + formID + '_' + fieldID].hasOwnProperty( 'timepicker' )
					) {
						properties = window['wpforms_' + formID + '_' + fieldID].timepicker;
					} else if (
						typeof window['wpforms_' + formID] !== 'undefined' &&
						window['wpforms_' + formID].hasOwnProperty( 'timepicker' )
					) {
						properties = window['wpforms_' + formID].timepicker;
					} else if ( typeof wpforms_timepicker !== 'undefined' ) {
						properties = wpforms_timepicker;
					} else {
						properties = {
							scrollDefault: 'now',
							forceRoundTime: true,
						};
					}

					element.timepicker( properties );
				} );
			}
		}
	})
})(jQuery);