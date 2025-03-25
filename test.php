<form method="POST" action="">
    <input type="text" name="test">
    <input type="submit">
</form>



<?php

$lemail=$_POST['test'];

echo $lemail;

function mdpAlea(): void {
        $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#-_\/:!+*$%;,";
        $nb_caract = 12;
        $pass = "";
        for($u = 1; $u <= $nb_caract; $u++) {
            $nb = strlen($chaine);
            $nb = mt_rand(0,($nb-1));
            $pass.=$chaine[$nb];
        }
        echo "Mot de passe comprenant 12 caractères : ".$pass."</br></br>";
        #HASH DU MDP
        $empreinte= hash('sha256',$pass);
        echo "Votre mdp hashé : ".$empreinte;
    }
    
        

if($lemail=="test"){
    $newmdp = mdpAlea();
    echo $newmdp;

} else {
    echo "error";
}

?>