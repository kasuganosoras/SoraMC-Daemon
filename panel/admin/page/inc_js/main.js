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
					document.getElementById("online").innerHTML=xmlhttp.responseText;
					var autorestart = document.getElementById("autorestart").value;
					if(xmlhttp.responseText=="<span class=\"text-danger\">服务器离线</span>"){
						document.getElementById("status").innerHTML="<span class=\"text-danger\">已停止</span>";
						if(autorestart=="true"){
							xmlhttp.open("GET","system.php?s=start&t=" + Math.random(),true);
							xmlhttp.send();
						}
					}
					else{
						document.getElementById("status").innerHTML="<span class=\"text-info\">运行中</span>";
						var online;
						online = xmlhttp.responseText.substring(xmlhttp.responseText.indexOf(">"),xmlhttp.responseText.indexOf("</"));
						console.log(online);
					}
				}
			}
			xmlhttp.open("GET","status.php?t=" + Math.random(),true);
			xmlhttp.send();
		}
		setInterval("online()",10000);
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
					showinfo("执行操作",xmlhttp.responseText);
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
					showinfo("执行操作",xmlhttp.responseText);
				}
			}
			xmlhttp.open("GET","system.php?s=stop&t=" + Math.random(),true);
			xmlhttp.send();
		}