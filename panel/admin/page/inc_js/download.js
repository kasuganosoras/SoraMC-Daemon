function getVersionList(Version){
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					document.getElementById("show").innerHTML = xmlhttp.responseText;
					//document.getElementById("show").style.display='';
				}
			}
			xmlhttp.open("GET","http://server.niconicocraft.com/jarlist/phpmc.php?version=" + Version,true);
			xmlhttp.send();
		}
		function showVersion(){
			var version = document.getElementById("type").value;
			getVersionList(version);
		}
		function download(Version,filename){
			showinfo("提示信息","正在下载版本，请稍后...");
			document.getElementById('dstatus').innerHTML="正在下载中...";
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					showinfo("提示信息",xmlhttp.responseText);
					location='download.php';
				}
			}
			xmlhttp.open("GET","?s=download&version=" + Version + "&file=" + filename,true);
			xmlhttp.send();
		}