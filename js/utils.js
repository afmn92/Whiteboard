var debounce = function (func, threshold, execAsap) {
	var timeout;
	return function debounced () {
		var obj = this, args = arguments;
		function delayed () {
			if (!execAsap)
				func.apply(obj, args);
			timeout = null; 
		};
		if (timeout)
			clearTimeout(timeout);
		else if (execAsap)
			func.apply(obj, args);
		timeout = setTimeout(delayed, threshold || 100); 
	};
}

var get = function (id) {
	return document.getElementById(id)
}

function zoomOutMobile() {
  var viewport = document.querySelector('meta[name="viewport"]');
  if ( viewport ) {
    viewport.content = "initial-scale=0.1";
    viewport.content = "width=1200";
  }
}
