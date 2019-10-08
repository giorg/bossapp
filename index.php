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

// dont touch the HTML without checking on ingame browser. It probably wont work :)


if ( !empty(htmlspecialchars($_POST["bosskilled"]))) {    
        $bosskilled = htmlspecialchars($_POST["bosskilled"]); 
}else { $bosskilled = 0; }
if ( !empty(htmlspecialchars($_POST["ago"]))) {    
        $ago = htmlspecialchars($_POST["ago"]); 
}else { $ago = 0; }
// checkbox doesnt work on ingame browser... 
//if ( !empty(htmlspecialchars($_POST["wasnotme"]))) {    
//        $wasnotme = htmlspecialchars($_POST["wasnotme"]); 
//}else { $wasnotme = 0; }
if ( !empty(htmlspecialchars($_POST["change_submits"]))) {    
        $change_submits = htmlspecialchars($_POST["change_submits"]); 
}else { $change_submits = 0; }

$cs = htmlspecialchars($_GET["checksum"]);if(empty($cs)){$cd=htmlspecialchars($_POST["checksum"]);}
$user = htmlspecialchars($_GET["user"]);if(empty($user)){$cd=htmlspecialchars($_POST["user"]);}

if( !empty($user) && !empty($cs)){
    $key = "m0f0KFI5I3cBfCU8BFakw0F6bnqqtSFO"; // key from app.ryzom.com
    $hash =     hash_hmac('sha1', $user , $key);
    if ( $hash == $cs){
        $name = explode(",", join("," , unserialize( base64_decode( $user ) )))[3];
        $faction = explode(",", join("," , unserialize( base64_decode( $user ) )))[8];
                $logging_time = explode(" " ,explode(",", join("," , unserialize( base64_decode( $user ) )))[0])[1];
    }     
        
        if ( time() - $logging_time > 86400 ){
                echo "<tr><td><h1>Timeout!</h1></td></tr><tr><td> Sorry, try to maybe open the app again?</td></tr><tr><td>(You opened app over a day ago if I count right...)</td></tr>";
        }
        else{
      
    $bosses = explode("\n", file_get_contents('bosses.txt' ));

    // $names = ...
    //if (in_array($name, $names)) {            
    if ( $faction == "marauder") {
        if( $bosskilled > 0 && $bosskilled <count($bosses) ) {
            $aika = explode("\n", file_get_contents('ajat.txt' ));
            $kaikki_ajat = explode("\n", file_get_contents('kaikki_ajat.txt' ));

                if ( $ago >0){
                        //$eri_aika = time() - ( $ago * 60*60);
                        //if( $aika[$bosskilled] > $eri_aika){}
                        $aika_just_nyt = time() - ( $ago * 60*60); 
                } else {
                        $aika_just_nyt = time(); // if time is not given, it's now
                }
                        
            $aika[$bosskilled] =  $aika_just_nyt;
            $kaikki_ajat[$bosskilled] = ( $kaikki_ajat[$bosskilled] . "," . $aika_just_nyt ) ;

            $aika = implode("\n",$aika);
            file_put_contents( 'ajat.txt', $aika);
            $kaikki_ajat = implode("\n",$kaikki_ajat);
            file_put_contents( 'kaikki_ajat.txt', $kaikki_ajat);

            $killer = explode("\n", file_get_contents('killer.txt' ));
                        
                // checkbox not working on ingame browser......... >_____<
                //if( $wasnotme = "on"){
                //        $killer[$bosskilled] =    $name ."*";
                //}else{
                $killer[$bosskilled] =    $name;
                //}
            $killer = implode("\n",$killer);
            file_put_contents( 'killer.txt', $killer);
            echo '<tr><td></td><td></td><td><h2>Submitted.</h2></td>';

            echo '<form action="" method="post">';
            echo '<input type="hidden" name="checksum" value="' . $cs . '">';
            echo '<input type="hidden" name="user" value="' . $user . '">';
            echo '<tr><td></td><td></td><td><input type="submit" value="Log another">';
            echo '</form></tr>';   

        }else{
                
            
                echo '<tr><td></td><td></td><td><h2>Log a boss:</h2></td></tr>';
                echo '<tr><td></td><td><form action="" method="post"><input type="hidden" name="checksum" value="' . $cs . '"><input type="hidden" name="user" value="' . $user . '">';                        
                echo '<tr><td></td><td></td><td>' . file_get_contents('select_option.txt' ) . "</td></tr>";
                //echo "<tr><td></td><td><td><input type='checkbox' name='wasnotme'> Rumor/I wasn't there</td></tr>"; // Not working on ingame browser
                echo "<tr><td></td><td><td><input type='number' value='0' name='ago'> hours ago</td></tr>";
                echo '<tr><td></td><td></td><td><input type="submit" value="           Log the boss          "></form></td></tr>';                                        

                echo '<tr><td></td><td><form action="change.php" method="post"><input type="hidden" name="change_submits" value="1"><input type="hidden" name="checksum" value="' . $cs . '"><input type="hidden" name="user" value="' . $user . '">';                        
                echo '<tr><td></td><td></td><td><input type="submit" value="       Delete your submit      "></form></td></tr>';                                        
                                        
        
        }
                
        // all data is handled, let's make the list
                
        $aika = explode("\n", file_get_contents('./ajat.txt' ));
        $killer = explode("\n", file_get_contents('./killer.txt' ));
        $aika_nyt = time();
                
        $timebosskiller = array();
                
        // every boss, 0 is empty... cant remember why anymore, just blame me if this causes problems :D
        for( $n = 1 ; $n < count($bosses) ; $n++ ){
                if($aika[$n] > 0){
                        array_push( $timebosskiller , [ $aika_nyt - $aika[$n] , $bosses[$n] , $killer[$n] ]);
                }
        }
                
        // order by time
        function cmp($a, $b){  return $a[0] - $b[0];}
        usort($timebosskiller, 'cmp');              
        
        $j1 = array();
        $j2 = array();
        $j3 = array();                
        $j4 = array();                
        $j5 = array();
        $j6 = array();
        
        for( $n=0; $n<count($timebosskiller); $n++){
                if(      $timebosskiller[$n][0] < 3600)  {array_push( $j1, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/60) .      " min ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}
                else if( $timebosskiller[$n][0] < 86400) {array_push( $j2, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/3600) .    " h ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}      
                else if( $timebosskiller[$n][0] < 172800){array_push( $j3, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/3600) .    " h ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}      
                else if( $timebosskiller[$n][0] < 259200){array_push( $j4, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/86400,1) . " days ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}      
                else if( $timebosskiller[$n][0] < 345600){array_push( $j5, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/86400,1) . " days ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}      
                else if( $timebosskiller[$n][0] < 432000){array_push( $j6, "<tr><td></td><td><u>" . $timebosskiller[$n][1] . "</u></td><td>in " . round( $timebosskiller[$n][0]/86400,1) . " days ago</td><td>by " . $timebosskiller[$n][2] . "</td></tr>");}      
                
        }
        echo "<tr><td><pre> </pre></td></tr><tr><td></td><td></td><td><h2>Killed in less than 48h:</h2></td></tr><br>";
        
        if( count($j1) + count($j2) + count($j3) <1){
                echo "<tr><td></td><td></td><td>(none)</td></tr>";
        }else{ 
               echo implode($j1 ). "<tr><td><pre> </pre></td></tr>";
               echo implode($j2 ). "<tr><td><pre> </pre></td></tr>";
               echo implode($j3 );                        
        }

        echo "<tr><td><pre> </pre></td></tr><tr><td></td><td></td><td><h2>Could be up:</h2></td></tr>";                                
        if( count($j4) <1 ){ echo "<tr><td></td><td></td><td>(none)</td></tr>";} else {echo implode($j4 );};
        echo "<tr><td><pre> </pre></td></tr><tr><td></td><td></td><td><h2>Could still be up:</h2></td></tr>";                                                
        if( count($j5) <1 ){ echo "<tr><td></td><td></td><td>(none)</td></tr>";} else {echo implode($j5 );};
        echo "<tr><td><pre> </pre></td></tr><tr><td></td><td></td><td><h2>Probably already dead:</h2></td></tr>";
        if( count($j6) <1 ){ echo "<tr><td></td><td></td><td>(none)</td></tr>";} else {echo implode($j6 );};
        echo "<tr><td><pre> </pre></td></tr><tr><td></td><td></td><td>Others have old or no data</td></tr>";
 

        } // not marauder
        else { echo "<tr><td><h2>Oi, go make yer rite!</h2></td><td><h2>No nation huggers allowed.</h2></td></tr>";}
        
    } // timeout else end

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