var ajax = function(url, obj) {
	$('body').css('cursor','wait');
	if (obj == undefined) obj = {};
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: 'POST',
			url: url+'.php',
			data: obj,
			dataType: 'json',
			cache: false
		})
			.done(function (response) {
				if (response.status != 'ok') {
					console.log(response)
					reject(response);
				};
				resolve(response);
			})
			.always(function (response) {
				$('body').css('cursor','auto');
			})
			.fail(function (response) {
				var error = response.responseText;
				console.log(response.responseText);
				reject(error);
			});
	});
};
var get_form = function(f) {
	var fd = $(f).serializeArray();
	var d = {};
	d.required = [];
	$(fd).each(function (k, v) {
		if (d[this.name] !== undefined) {
			if (!Array.isArray(d[this.name])) {
				d[this.name] = [d[this.name]];
			}
			d[this.name].push(this.value);
		} else {
			d[this.name] = this.value;
			if ($(f).find("[name=\"" + this.name + "\"]").prop('required')) {
				d.required.push(this.name);
			}
		}
	});
	return d;
};
var get_template = function(template) {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: 'GET',
			url: 'templates/'+template+'.php',
			cache: false
		})
		.done(function (data) {
			resolve(data);
		})
		.fail(function (data) {
			reject(data);
		});
	});

};

var messages = {
	dialog:function(obj){
		if(obj.show){
			$('.pop-messages').addClass('active').find('.body').html(obj.message);
		}else{
			$('.pop-messages').removeClass('active').find('.body').html('');
		}
		$('.pop-messages [data-button="close"]').unbind('click').click(function(){
			messages.dialog({show:false});
		});
		if('callback' in obj){
			$('.pop-messages [data-button="ok"]').show().unbind('click').click(function(){
				messages.dialog({show:false});
				obj.callback();
			});
		}else{
			$('.pop-messages [data-button="ok"]').hide();
		}
	}
}