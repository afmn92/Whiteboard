function removeUser(id){
	var ans = confirm("Delete this user?");
	if(ans){//if true delete row
		window.location.assign("../adminutils/deleteUser.php?id="+id);
	}
}

function adminUser(id){
	var ans = confirm("Make this user an admin?");
	if(ans){
		window.location.assign("../adminutils/makeAdmin.php?id="+id);
	}
}

function unadminUser(id){
	var ans = confirm("Remove admin status?");
	if(ans){
		window.location.assign("../adminutils/unmakeAdmin.php?id="+id);
	}
}

function removeRoom(board){
	var ans = confirm("Delete this room?");
	if(ans){
		window.location.assign("../adminutils/deleteRoom.php?board="+board);
	}
}

function gotoRoom(board){
	var ans = confirm("Go to this room?");
	if(ans){
		window.location.assign("../board/"+board);
	}
}