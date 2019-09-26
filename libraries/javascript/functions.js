var ajax = function ajax(url, obj) {
	$('.day-cell,body').css('cursor','wait');
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
					Swal.fire({ type: 'error', html: response.message });
					reject(response);
				};
				resolve(response);
			})
			.always(function (response) {
				$('.day-cell,body').css('cursor','pointer');
			})
			.fail(function (response) {
				var error = response.responseText;
				console.log(response.responseText);
				reject(error);
			});
	});
};
var get_form = function get_form(f) {
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
var get_template = function get_template(template) {
	return new Promise(function (resolve, reject) {
		var ajax = $.ajax({
			type: 'GET',
			url: 'templates/'+template+'.php',
			cache: false
		}).done(function (data) {
			resolve(data);
		}).fail(function (data) {
			reject(data);
		});
	});

};