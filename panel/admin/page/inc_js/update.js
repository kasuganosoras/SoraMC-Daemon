function getversion(Version){
			var xmlhttp;
			var now = parseInt(Version);
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					document.getElementById("version").innerHTML="Ver.3.0." + xmlhttp.responseText;
					if(parseInt(xmlhttp.responseText)==now){
						document.getElementById("text").innerHTML="已是最新版本";
						document.getElementById("updatebtn").disabled='disabled';
						document.getElementById("updatebtn").innerHTML='无需更新';
					}
					else{
						document.getElementById("text").innerHTML="发现更新！";
					}
				}
			}
			xmlhttp.open("GET","http://temp.tcotp.cn/phpmc/version/?ver=" + now + "&t=" + Math.random(),true);
			xmlhttp.send();
		}
		function update(){
			showinfo("提示信息","系统正在进行更新，请勿操作");
			document.getElementById("updatebtn").innerHTML='更新中';
			var xmlhttp;
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}
			else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=='ok'){
						showinfo('提示信息','系统组件更新完成！');
					}
					if(xmlhttp.responseText=='new'){
						showinfo('提示信息','您的系统已经是最新版本');
					}
					else{
						showinfo('提示信息','系统更新失败！请稍后重试或联系作者！');
					}
					document.getElementById("updatebtn").innerHTML='立即更新';
				}
			}
			xmlhttp.open("GET","?s=update&t=" + Math.random(),true);
			xmlhttp.send();
		}