class DrawableCanvas {
	constructor(canvas_id, enable_draw) {
		this.enable_draw = enable_draw
		this.mouseDown = 0;
		this.lastX = this.lastY = -1;
		this.arrayMouse = []
	    this.canvas = document.getElementById(canvas_id);

		// If the browser supports the canvas tag, get the 2d drawing context for this canvas
		if (this.canvas.getContext)
			this.ctx = this.canvas.getContext('2d');

		// Check that we have a valid context to draw on/with before adding event handlers
		if (this.ctx) {
			if (this.enable_draw) {
				this.canvas.addEventListener('mousedown', this.mouse_down.bind(this), false);
				this.canvas.addEventListener('mousemove', this.mouse_move.bind(this), false);
				this.canvas.addEventListener('mouseup', this.mouse_up.bind(this), false);
				this.canvas.addEventListener('touchstart', this.touch_start.bind(this), false);
				this.canvas.addEventListener('touchmove', this.touch_move.bind(this), false);
				this.canvas.addEventListener('touchend', this.touch_end.bind(this), false);
			}
		}
	}

	draw_line(x1, y1, x2, y2) {
		this.ctx.lineWidth = 1.5;
		this.ctx.strokeStyle = "rgba(0,0,0,1)";
		this.ctx.lineCap = "round";
		this.ctx.beginPath();
		this.ctx.moveTo(x1, y1);
		this.ctx.lineTo(x2, y2);
		this.ctx.stroke();
		this.ctx.closePath();
	}

	draw_lines(lines) {
		if (lines.length < 2) return
		for (var i = 0; i < lines.length-1; i++) {
			this.draw_line(lines[i][0], lines[i][1], lines[i+1][0], lines[i+1][1])
		}
	}

	// Get the touch position relative to the top-left of the canvas
	// When we get the raw values of pageX and pageY below, they take into account the scrolling on the page
	// but not the position relative to our target div. We'll adjust them using "target.offsetLeft" and
	// "target.offsetTop" to get the correct values in relation to the top left of the canvas.
	get_touch_position(e) {
		if (!e) var e = event;
		if(e.touches) {
			if (e.touches.length == 1) { // Only deal with one finger
				var touch = e.touches[0]; // Get the information for finger #1
				this.touchX = touch.pageX - touch.target.offsetLeft;
				this.touchY = touch.pageY - touch.target.offsetTop;
				return true
			}
		}
		return false
	}

	// Get the current mouse position relative to the top-left of the canvas
	get_mouse_position(e) {
		if (!e) var e = event;
		if (e.offsetX) {
			this.mouseX = e.offsetX;
			this.mouseY = e.offsetY;
		}
		else if (e.layerX) {
			this.mouseX = e.layerX;
			this.mouseY = e.layerY;
		}
	}

	load_image(url) {
		this.imageObj = new Image()
		this.imageObj.onload = this.load_image_callback.bind(this)
		this.imageObj.src = url
	}
	load_image_callback() {
		this.ctx.drawImage(this.imageObj, 0, 0)
	}

	clear_canvas() {
		this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
	}

	mouse_down(e) {
		this.arrayMouse.length = 0;
		this.mouseDown = 1;
		this.get_mouse_position(e);
		this.lastX = this.mouseX;
		this.lastY = this.mouseY;
		this.draw_line(this.lastX, this.lastY, this.mouseX, this.mouseY);
		this.arrayMouse.push([this.mouseX, this.mouseY]);
	}

	mouse_move(e) { 
		if (this.mouseDown == 1) {
			this.get_mouse_position(e);
			this.draw_line(this.lastX, this.lastY, this.mouseX, this.mouseY);
			this.lastX = this.mouseX;
			this.lastY = this.mouseY;
			this.arrayMouse.push([this.mouseX, this.mouseY]);
			if (e.target == this.canvas)
				event.preventDefault();
			}
	}

	mouse_up() {
		this.mouseDown = 0;
		if (this.arrayMouse.length > 1)
			chat_send_update('', 'draw', username, JSON.stringify(this.arrayMouse), false)
		this.clear_canvas()
	}

	touch_start(e) {
		// Update the touch co-ordinates
		if (this.get_touch_position(e)) {
			this.arrayMouse.length = 0;
			this.lastX = this.touchX;
			this.lastY = this.touchY;
			this.draw_line(this.lastX, this.lastY, this.touchX, this.touchY);
			this.arrayMouse.push([this.touchX, this.touchY]);
			// Prevents an additional mousedown event being triggered
			if (e.target == this.canvas)
				event.preventDefault();
		}
	}

	touch_move(e) { 
		// Update the touch co-ordinates
		if (this.get_touch_position(e)) {
			// No need to check if the touch is engaged.
			// A touchmove event will always be contact with the screen
			this.draw_line(this.lastX, this.lastY, this.touchX, this.touchY); 
			this.lastX = this.touchX;
			this.lastY = this.touchY;
			this.arrayMouse.push([this.touchX, this.touchY]);
			// Prevent a scrolling action as a result of this touchmove triggering.
			if (e.target == this.canvas)
				event.preventDefault();
		}
	}

	touch_end() {
		if (this.arrayMouse.length > 1)
			chat_send_update('', 'draw', username, JSON.stringify(this.arrayMouse), false)
		this.clear_canvas()
	}
}

// *****************************************************************************
// *****************************************************************************

var pboard = null
var dboard = null
window.addEventListener("load", function() {
	pboard = new DrawableCanvas('personal_board', true)
	dboard = new DrawableCanvas('display_board', false)
})
