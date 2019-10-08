<!DOCTYPE html>
<html lang="en">

<head>
	<title>Bosslogger - stats</title>
	<meta charset="utf-8">
<style>
	body,html{
		background-color:#000000;
		color: white;
		text-align:center;
	}
	pre {white-space: nowrap; display: block; padding-top:0px;}
</style>
</head>
<body>
<pre>            </pre><img src="https://api.ryzom.com/data/cache/guild_icons/11262470073296988_b.png">
<h3 style="color:#650005;" ><pre>      </pre>ShadoWalkers</h3><br>

<?php

if ( !empty(htmlspecialchars($_POST["bosskilled"]))) {
	$bosskilled = htmlspecialchars($_POST["bosskilled"]);
}
$cs = htmlspecialchars($_GET["checksum"]);
$user = htmlspecialchars($_GET["user"]);
if(empty($cs)){$cd=htmlspecialchars($_POST["checksum"]);}
if(empty($user)){$cd=htmlspecialchars($_POST["user"]);}


if( !empty($user) && !empty($cs)){
	$key = "m0f0KFI5I3cBfCU8BFakw0F6bnqqtSFO";
 	$hash =  hash_hmac('sha1', $user , $key);
 	if ( $hash == $cs){
		$name = explode(",", join("," , unserialize( base64_decode( $user ) )))[3];
		$faction = explode(",", join("," , unserialize( base64_decode( $user ) )))[8];
                //echo "<hr>" . $faction . "  " .  $name . "<hr>";
        }
        else{echo "cant get the checksum";}

	if ( $faction == "marauder") {
		//$aika = explode("\n", file_get_contents('ajat.txt' ));
		$kaikki_ajat = explode("\n", file_get_contents('kaikki_ajat.txt' ));
		$bosses = explode("\n", file_get_contents('bosses.txt' ));
    		echo '<form action="index.php" method="pre"><input type="hidden" name="checksum" value=' . $cs . '><input type="hidden" name="user" value=' . $user . '>';
                

		echo "<table border='1'>";
		for( $a = 1; $a < count($bosses) ; $a++){
			echo "<tr><td><b>" . $bosses[$a] . "</b></td>";
                        $aikajuttu = explode(",",$kaikki_ajat[$a]);
                        for( $b=1 ; $b < count($aikajuttu) ; $b++){
                                if ( $aikajuttu[$b] > 0 ){
                                        echo "<td>" . gmdate("Y-m-d H:i:s",$aikajuttu[$b]) . "</td>";
				}
                        }
            echo "</tr>";
						
		}


	}
        else echo "<h2><pre>  </pre>Oi, go make yer rite,<br>no nation huggers allowed.</h2>";
}else echo "<p>Error, something not right with getting data from server.</p><p>Think this doesnt work ingame, try bowser maybe</p>";



?>
</body>
</html>


