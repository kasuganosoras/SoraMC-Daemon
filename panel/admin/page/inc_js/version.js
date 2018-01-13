function deletef(filename){
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
					location='version.php';
				}
			}
			xmlhttp.open("GET","?s=delete&file=" + filename,true);
			xmlhttp.send();
		}