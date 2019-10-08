<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bosslogger</title>
    <meta charset="utf-8">
<style>
    body,html{
        background-color:#000000;
        color: white;
    }

</style>
</head>
<body>
<table>
   <tr><td></td><td></td><td><pre>    <img src="https://api.ryzom.com/data/cache/guild_icons/11262470073296988_b.png"></pre></td><td></td></tr>
   <tr><td></td><td></td><td><h3 style="color:#650005;" >ShadoWalkers</h3></td><td></td></tr>
   <tr><td><pre> </pre></td></tr>
<?php


if ( !empty(htmlspecialchars($_POST["change_submits"]))) {    
        $change_submits = htmlspecialchars($_POST["change_submits"]); 
}else { $change_submits = 0; }

if ( !empty(htmlspecialchars($_POST["changevalue"]))) {    
        $changevalue = htmlspecialchars($_POST["changevalue"]); 
}else { $changevalue = 0; }



$cs = htmlspecialchars($_GET["checksum"]);if(empty($cs)){$cs=htmlspecialchars($_POST["checksum"]);}
$user = htmlspecialchars($_GET["user"]);if(empty($user)){$user=htmlspecialchars($_POST["user"]);}

if( !empty($user) && !empty($cs)){
    $key = "m0f0KFI5I3cBfCU8BFakw0F6bnqqtSFO"; // app.ryzom.com key
    $hash = hash_hmac('sha1', $user , $key);
    if ( $hash == $cs){
        $name = explode(",", join("," , unserialize( base64_decode( $user ) )))[3];
        $faction = explode(",", join("," , unserialize( base64_decode( $user ) )))[8];
    }     
          
    $bosses = explode("\n", file_get_contents('bosses.txt' ));

	
	// $names = ...
    //if (in_array($name, $names)) {        
    if ( $faction == "marauder") {
        
        echo "<tr><td>You are: " . $name . "</td></tr>";
        $killer = explode("\n", file_get_contents('killer.txt' ));
        $aika = explode("\n", file_get_contents('ajat.txt' ));                     
                
            if ($changevalue > 0 ){
			        // changing value is not workin on ingame browser, just delete
                    // check name is the killer before really deleting submit...
                    if( $killer[$changevalue] == $name ){
                            $aika[$changevalue] = "0";
                            $killer[$changevalue] = "(deleted)";                              

                            $aika = implode("\n",$aika);
                            $killer = implode("\n",$killer);      
                            
                            file_put_contents( 'ajat.txt', $aika);
                            file_put_contents( 'killer.txt', $killer);
                            echo "<tr><td>Entry deleted.</td></tr>";                                
                    }else{
                            echo "<tr><td>Cant delete, You're not the killer</td></tr>";
                    }
            
            }

            for( $n=0; $n<count($killer); $n++){               
                if ( $killer != "unsure"){                             
                    //if( $name == $killer[$n] || ($name . "*") == ($killer[$n])){
					// not working on ingame browser, let's simplify
                    if( $name == $killer[$n] ){                                
                            echo "<tr>";
                            echo "<td>" . $bosses[$n] . "</td>";
                            echo "<td>". gmdate("Y-m-d H:i:s",$aika[$n]) . " (UTC)</td>"; 
                            echo "<td><form action='' method='post'><input type='hidden' name='changevalue' value='". $n . "'><input type='hidden' name='checksum' value='" . $cs . "'><input type='hidden' name='user' value='" . $user . "'><input type='submit' value='Delete this entry'></form></td>";
                            echo "</tr>";
                    }
                }
            }
                
                echo "<tr><td><form action='index.php' method='get'><input type='hidden' name='checksum' value='" . $cs . "'><input type='hidden' name='user' value='" . $user . "'><input type='submit' value='Back to logger..'></form></td></tr>";

        } // not marauder
        else { echo "<tr><td><h2>Oi, go make yer rite!</h2></td><td><h2>No nation huggers allowed.</h2></td></tr>";}
        

} // checksum isnt matching
else echo "<tr><td><h2>Error!</h2></td><td>Something not right with getting data from server</td></tr>";

?>
   <tr>
      <td><pre>                </pre></td>        
      <td><pre>                </pre></td>        
      <td><pre>                </pre></td>        
      <td><pre>                </pre></td>        
      <td><pre>                </pre></td>        
      <td><pre>                </pre></td>
      <td><pre>                </pre></td>      
   </tr>
</table>
</body>
</html>