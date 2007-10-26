function setID(form, field)
{
	document[form][field].value = "";
	var idArray = document[form]['ID'];
	for(var i = 0; i < idArray.length; i++) {
		if(idArray[i].checked) {
			if(!IDstr) { var IDstr = idArray[i].value; }
			else { var IDstr = "|"+idArray[i].value; }
			document[form][field].value += IDstr;
		}
	}
}

function randomPass(form, field)
{
	var randPass = 'tam';
	var nums = "0123456789";
	for(var i = 0; i < 3; i++) {
		var rnum = Math.floor(Math.random() * nums.length);
		randPass += nums.substring(rnum, rnum+1);
	}
	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for(var i = 0; i < 2; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randPass += chars.substring(rnum,rnum+1);
	}
	document[form][field].value = randPass;
}

function randomString(form, field)
{
	var randString = '';
	//var chars = "0123456789abcdefghijklmnopqrstuvwxyz";
	var chars = "0123456789abcdef";//ez kell csak md5 szerû-höz
	for(var i = 0; i < 32; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randString += chars.substring(rnum, rnum+1);
	}
	document[form][field].value = randString;
}