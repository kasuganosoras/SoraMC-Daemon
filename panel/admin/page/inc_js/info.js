function submit(){
			var servername = document.getElementById('servername').value;
			var user = document.getElementById('user').value;
			var pass = document.getElementById('pass').value;
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
					location='info.php';
				}
			}
			xmlhttp.open("GET","?user=" + user + "&pass=" + pass + "&servername=" + servername + "&t=" + Math.random(),true);
			xmlhttp.send();
		}