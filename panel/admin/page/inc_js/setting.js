function save() {
                var maxram = document.getElementById("maxram").value;
                var jar = document.getElementById("jar").value;
                var server_port = document.getElementById("server_port").value;
                var server_ip = document.getElementById("server_ip").value;
                var rcon_port = document.getElementById("rcon_port").value;
                var rcon_password = document.getElementById("rcon_password").value;
                var max_players = document.getElementById("max_players").value;
                var gamemode = document.getElementById("gamemode").value;
                var difficulty = document.getElementById("difficulty").value;
                var level_name = document.getElementById("level_name").value;
                var motd = document.getElementById("motd").value;
                var online_mode = document.getElementById("online_mode").value;
                var hardcore = document.getElementById("hardcore").value;
                var enable_command_block = document.getElementById("enable_command_block").value;
                var white_list = document.getElementById("white_list").value;
                var view_distance = document.getElementById("view_distance").value;
                var spawn_animals = document.getElementById("spawn_animals").value;
                var spawn_monsters = document.getElementById("spawn_monsters").value;
                var pvp = document.getElementById("pvp").value;
				var servername = document.getElementById("servername").value;
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        showinfo("提示信息",xmlhttp.responseText);
                    }
                }
                xmlhttp.open("GET", "?t=" + Math.random() + "&maxram=" + maxram + "&jar=" + jar + "&server-port=" + server_port + "&server-ip=" + server_ip + "&rcon_port=" + rcon_port + "&rcon_password=" + rcon_password + "&max-players=" + max_players + "&gamemode=" + gamemode + "&difficulty=" + difficulty + "&level-name=" + level_name + "&motd=" + motd + "&online-mode=" + online_mode + "&hardcore=" + hardcore + "&enable-command-block=" + enable_command_block + "&white-list=" + white_list + "&view-distance=" + view_distance + "&spawn-animals=" + spawn_animals + "&spawn-monsters=" + spawn_monsters + "&pvp=" + pvp + "&servername=" + servername, true);
                xmlhttp.send();
            }