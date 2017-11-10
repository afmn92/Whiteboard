// Callback to handle browser window resize events
function resize_canvas() {
	resize_canvas_pboard = get("personal_board")
	resize_canvas_dboard = get("display_board")
	resize_canvas_dboard_context = resize_canvas_dboard.getContext('2d');
	
	temp_cnvs = document.createElement('canvas');
	temp_cntx = temp_cnvs.getContext('2d');
	
	temp_cnvs.width = document.body.scrollWidth - 205
	temp_cnvs.height = document.documentElement.clientHeight - 100
	
	temp_cntx.drawImage(resize_canvas_dboard, 0, 0);
	//document.body.scrollWidth - 265
	//document.body.clientWidth - 205
	resize_canvas_dboard.width = resize_canvas_pboard.width = document.body.clientWidth - 205
	//document.documentElement.clientHeight
	//document.body.clientHeight - 100
	resize_canvas_dboard.height = resize_canvas_pboard.height = document.body.clientHeight - 100
	
	resize_canvas_dboard_context.drawImage(temp_cntx, 0, 0);
}

// *****************************************************************************
// *****************************************************************************

var username = ''
var rload = 1;

window.addEventListener("load", function() {
	username = get('LOGIN_USERNAME')
	if (username)
		username = username.value
	else
		username = 'unknown'
	resize_canvas()
	window.setTimeout(resize_canvas, 100)
	window.setInterval(zoomOutMobile, 5000)
	window.addEventListener('resize', debounce(resize_canvas, 250, false), true)
	document.getElementById('board_id').value = board_id
	document.getElementById('load_image').addEventListener('click', function() {
		document.querySelector('form#file_uploader #userfile').click()
		// document.querySelector('form#file_uploader').submit()
		// dboard.load_image('/uploads/Screen_Shot_2017_10_30_at_2.59.34_PM.png')
	}, true)
})
