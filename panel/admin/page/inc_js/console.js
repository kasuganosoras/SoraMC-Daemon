var backcommand;
		var nextcommand;
		var status;
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
					if(status!=xmlhttp.responseText){
						showinfo("提示信息","服务器状态发生变化");
						status = xmlhttp.responseText;
					}
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
					showinfo("服务器返回结果",xmlhttp.responseText);
					document.getElementById("command").value = "";
				}
			}
			xmlhttp.open("GET","?command=" + command + "&t=" + Math.random(),true);
			xmlhttp.send();
		}
		function runcmd(){
			var command;
			command = document.getElementById("command").value;
			if(!command){
				showinfo("提示信息","抱歉，命令不能为空");
			}
			else{
				backcommand = command;
				issuscommand(command);
			}
		}
		document.onkeydown=function(event){
            var e = event || window.event || arguments.callee.caller.arguments[0];         
            if(e && e.keyCode==13){
                runcmd();
            }
			if(e && e.keyCode==38){
				nextcommand = document.getElementById("command").value;
                document.getElementById("command").value=backcommand;
            }
			if(e && e.keyCode==40){
				backcommand = document.getElementById("command").value;
                document.getElementById("command").value=nextcommand;
            }
        };
		function doit(){
			var docommand = document.getElementById("type").value;
			var playername = document.getElementById("playername").value;
			issuscommand(docommand + playername);
		}