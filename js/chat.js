function past_tense(action) {
	data = { 'chat': 'wrote', 'draw': 'drew', 'upload': 'uploaded' }
	return data[action]
}

function chat_get_update() {
	httpRequest = new XMLHttpRequest()
	httpRequest.open('GET', '/update/' + board_id + '/' + last_seen_id, true);
	httpRequest.onreadystatechange = handleNewUpdates
	httpRequest.send()
}

function handleNewUpdates() {
	var chat_box = get('chat_data')
	if (httpRequest.readyState !== XMLHttpRequest.DONE) return
	window.setTimeout(chat_get_update, 1000)
	if (httpRequest.status !== 200) {
		console.log('There was a problem with handleNewUpdates().')
		return
	}
	for (let row of JSON.parse(httpRequest.responseText)) {
		if (row['action'] == 'upload') {
			dboard.load_image(row['data'])
		}
		if (row['action'] == 'draw') {
			dboard.draw_lines(JSON.parse(row['data']))
			// pboard.draw_lines(JSON.parse(row['data']))
		}
		line = row['user'] + ' ' + past_tense(row['action'])
		if (row['action'] == 'chat')
			line += ': ' + row['data']
		chat_box.value = chat_box.value + line + '\n'
		chat_box.scrollTop = chat_box.scrollHeight
		last_seen_id = row['id']					
	}
}

function chat_send_update(event, action, username, data, clear_chat_box) {
	if (data.length == 0) return
	if (clear_chat_box && event.keyCode !== 13) return
	httpRequest = new XMLHttpRequest()
	httpRequest.open('POST', '/update', true)
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState !== XMLHttpRequest.DONE) return
		if (httpRequest.status === 200) {
			if (clear_chat_box) chat_input.value = ''
		} else {
			console.log('There was a problem with chat_send_update().')
		}
	}
	httpRequest.send("board="+board_id+"&data="+data+"&action="+action+"&user="+username)
}

// *****************************************************************************
// *****************************************************************************

var last_seen_id = 0
var board_id = document.URL.split('/').slice(-1).pop()
var chat_input = ''

window.addEventListener("load", function() {
	chat_input = get('chat_input')
	window.setTimeout(chat_get_update, 500)
	chat_input.addEventListener("keyup", function(event) {
		chat_send_update(event, "chat", username, chat_input.value, true)
	}, true)
})
