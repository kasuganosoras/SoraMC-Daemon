function getnewversion(Version){
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	}
	else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var oldver = Version;
			var newver = xmlhttp.responseText;
			if(oldver != newver){
				newversioninfo(xmlhttp.responseText);
			}
		}
	}
	xmlhttp.open("GET","http://temp.tcotp.cn/phpmc/version/?ver=" + Version + "&s=" + Math.random(),true);
	xmlhttp.send();
}
function newversioninfo(Version){
	if (window.Notification){
		if (Notification.permission === 'granted'){
			var notification = new Notification('发现PHPMC版本更新！',{body:"PHPMC 当前有新版本可以升级，最新版本：" + Version + "\n请转到 管理器 > 更新系统 查看",icon:"./favicon.ico"});
		}
		else{
			Notification.requestPermission(
				function(result){
					if (result === 'denied' || result === 'default') {
						//Not do any things
					}
					else{
						var notification = new Notification('发现PHPMC版本更新！',{body:"PHPMC 当前有新版本可以升级，最新版本：" + Version + "\n请转到 管理器 > 更新系统 查看",icon:"./favicon.ico"});
					}
				}
			);
		};
	}
}