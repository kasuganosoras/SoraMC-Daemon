function online(){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="<span class=\"text-danger\">服务器离线</span>"){
						document.getElementById("status").innerHTML="<span class=\"text-danger\">服务器已停止</span>";
					}
					else{
						document.getElementById("status").innerHTML="<span class=\"text-info\">服务器运行中</span>";
					}
				}
			}
			xmlhttp.open("GET","status.php?t=" + Math.random(),true);
			xmlhttp.send();
		}
		setInterval("online()",10000);
		function getlog(){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					document.getElementById("logs").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","?s=logs&t=" + Math.random(),true);
			xmlhttp.send();
		}
		setInterval("getlog()",5000);
		function startserver(){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					alert(xmlhttp.responseText);
				}
			}
			xmlhttp.open("GET","system.php?s=start&t=" + Math.random(),true);
			xmlhttp.send();
		}
		function stopserver(){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					alert(xmlhttp.responseText);
				}
			}
			xmlhttp.open("GET","system.php?s=stop&t=" + Math.random(),true);
			xmlhttp.send();
		}
		function issuscommand(command){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					alert("Server Response:" + xmlhttp.responseText);
					document.getElementById("command").value = "";
				}
			}
			xmlhttp.open("GET","?command=say " + command + "&t=" + Math.random(),true);
			xmlhttp.send();
		}
		function runcmd(){
			var command;
			command = document.getElementById("command").value;
			if(!command){
				alert("抱歉，聊天内容不能为空");
			}
			else{
				issuscommand(command);
			}
		}