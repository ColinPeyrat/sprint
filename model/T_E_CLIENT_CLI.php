<?php

class T_E_CLIENT_CLI extends Model
{
    protected $_cli_id;
    protected $_cli_mel;
    protected $_cli_motpasse;
    protected $_cli_pseudo;
    protected $_cli_civilite;
    protected $_cli_nom;
    protected $_cli_prenom;
    protected $_cli_telfixe;
    protected $_cli_telportable;
    protected $_role;
    protected $_connected;

    public function verifUserData($mail,$mdp,$mdpconfirm,$pseudo,$civilite,$nom,$prenom,$tfixe,$tport){

        $m = new message();
        $error = false;
        $datas = array($mail,$mdp,$mdpconfirm,$pseudo,$civilite,$nom,$prenom);
        if(!($mdp == $mdpconfirm)){
            $m->setFlash("Les mots de passes doivent etre identiques");
            $error = true;
        }
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
             $m->setFlash("Veuillez saisir un E-Mail valide");
                         $error = true;

        }
        if(isset($tfixe) && !empty($tfixe)){
             if (!preg_match("#04\d{7,9}#", $tfixe)){
                $m->setFlash("Veuillez saisir un numéro de téléphone fixe valide");
                $error = true;
             }
         }
         if(isset($tport) && !empty($tport)){
             if (!preg_match("#06\d{7,9}#", $tport)){
                $m->setFlash("Veuillez saisir un numéro de téléphone portable valide");
                $error = true;
             }
         }
         if(empty($tport) && empty($tfixe)){
             $m->setFlash("Vous devez saisir obligatoirement au moins un numéro de téléphone");
             $error = true;
         }
        foreach ($datas as $data) {
            if(empty($data)){
                 $m->setFlash("Tous les champs sont obligatoires");
                 $error = true;
            }
        }

        return $error;
    }

    public function insertNewUser(){
        $req = db()->prepare("INSERT INTO t_e_client_cli (cli_mel, cli_motpasse, cli_pseudo, cli_civilite, cli_nom,cli_prenom,cli_telfixe,cli_telportable) VALUES (:mel, :motpase, :pseudo, :civilite,:nom,:prenom,:telfixe,:telport)");
        $req->execute(array(
            "mel" => $this->_cli_mel,
            "motpase" => $this->_cli_motpasse,
            "pseudo" => $this->_cli_pseudo,
            "civilite" => $this->_cli_civilite,
            "nom" => $this->_cli_nom,
            "prenom" => $this->_cli_prenom,
            "telfixe" => $this->_cli_telfixe,
            "telport" => $this->_cli_telportable,
            ));
    }


    public function userExistInDb()
    {
        $st = db()->prepare("select * from t_e_client_cli where cli_mel=:mel AND cli_motpasse=:password");
        $st->bindValue(":mel", $this->_cli_mel);
        $st->bindValue(":password", $this->_cli_motpasse);
        $st->execute();
        if ($st->rowCount() >= 1){

                $st = db()->prepare("select cli_id from t_e_client_cli where cli_mel=:mel");
                $st->bindValue(":mel", $this->_cli_mel);
                $st->execute();
                $f = $st->fetch();
                $client =  new T_E_CLIENT_CLI($f["cli_id"]);
                if($f["cli_id"] == 1){
                    $client->_role="Service vente";
                } else if($f["cli_id"] == 2){
                    $client->_role="Service communication";
                } else if($f["cli_id"] == 3){
                    $client->_role="Service client";
                }
                $client->_connected = true;
            return $client;
        } else
            $this->_connected = false;

    }
    public function displayInfo() {
        $info = array("Mail","Mot de passe","Pseudo","Civilité","Nom","Prenom","Téléphone Fixe","Téléphone Portable");
        $i = 0;
        if(isset($this->_role)){
            $info[] = "<strong>Votre role</strong>";
        }
        $value = array();
        foreach($this as $key) {
            $value[] = $key;
        }
        echo "<table class=\"table\">";
        echo "<tbody>";
        foreach ($info as $inf) {
            echo "<tr>";
            echo "<td>".$inf."</td>";
            echo "<td>".$value[$i + 1];
            if($value[$i + 1] == null)
                echo "";
            $i++;
            echo "</td>";


        }
        echo "</tbody>";
        echo "</table>";


    }
    public function modifyInfo($mail,$mdp,$pseudo,$civilite,$nom,$prenom,$tfixe,$tport){
        $this->__set("cli_mel",$mail);
        $this->__set("cli_motpasse",$mdp);
        $this->__set("cli_pseudo",$pseudo);
        $this->__set("cli_civilite",$civilite);
        $this->__set("cli_nom",$nom);
        $this->__set("cli_prenom",$prenom);
        $this->__set("cli_telfixe",$tfixe);
        $this->__set("cli_telportable",$tport);
    }

    public function checkIfUserDontExist($mail,$pseudo){
        $clients = $this::findAll();
        $dontExist = true;
        foreach($clients as $client){
            if($client->cli_mel == $mail || $client->cli_pseudo == $pseudo)
                $dontExist = false;
        }

        return $dontExist;
    }



    public function displayModifyInfo() {
        ?>
        <form class="form-horizontal" method="post" action="?r=cli/modify" role="form">
        <?php
        $info = array("Mail","Mot de passe","Pseudo","Civilité","Nom","Prenom","Téléphone Fixe","Téléphone Portable");
        $i = 0;


        foreach($this as $key=>$value) {
            $name[] = substr($key,5);
            $valu[] = $value;
        }

        foreach ($info as $inf) {

            if($valu[$i + 1] == null)
                $valuetext = "";
            else
                $valuetext = $valu[$i + 1];
            echo "<div class='form-group'>";
            echo "<label class='control-label col-sm-2'>$inf</label>";

            if($name[$i + 1] == "civilite") {
                ?>
                <div class='col-sm-10'>
                <select class="form-control" name="civilite">
                    <option value="M.">M.</option>
                    <option value="Mme">Mme</option>
                    <option value="Mlle">Mlle</option>
                </select>
                </div>
            </div>
                <?php
            } else {

                echo "<div class='col-sm-10'>";
                    echo "<input class='form-control' type=\"text\" name=\"".$name[$i + 1]."\" value=\"$valuetext\"><br>";
                    echo "</div>";
                echo "</div>";



             }

            $i++;

        }

        echo "<div class='form-group'>";
            echo "<label class='control-label col-sm-2'></label>";
            echo "<div class='col-sm-10'>";
                echo "<button type='submit' name='action' value='Modifier' class='btn btn-primary'>Modifier</button>";
            echo "</div>";
        echo "</div>";  
        echo "</form>";
    }

    public static function findById($id_cli){
        $c = new T_E_CLIENT_CLI($id_cli);
        return $c;
    }

    public static function displayCartModal(){
        ?>
        <!--Modal for ajax-->
        <div class="modal fade" id="myModalCart" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Produit ajouté</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Votre article a bien été ajouté au panier.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Continuer mes achats</button>
                        <button type="button" id="gotocart" class="btn btn-default" data-dismiss="modal">Voir mon panier</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModalFav" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Produit ajouté</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Votre article a bien été ajouté aux favoris.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Continuer mes achats</button>
                        <button type="button" id="gotofav" class="btn btn-default" data-dismiss="modal">Voir mes favoris</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="errorModal" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="text-danger modal-title"><i class="glyphicon glyphicon-remove"></i> Erreur</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Vous avez déja ajouté cet article !
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }


    public static function addToFav($id_game){
        $st = db()->prepare("SELECT * FROM t_j_favori_fav WHERE cli_id =:cli_id AND jeu_id =:jeu_id");
        $st->bindValue(":cli_id", $_SESSION["user"]->cli_id);
        $st->bindValue(":jeu_id", $id_game);
        $st->execute();
        if ($st->rowCount() == 0){
            $req = db()->prepare("INSERT INTO t_j_favori_fav(cli_id, jeu_id) VALUES (:cli_id, :jeu_id)");
            $req->execute(array(
            "jeu_id" => $id_game,
            "cli_id" => $_SESSION["user"]->cli_id
            ));
            return true;
        }
        else{ return false; }
    }

    public static function findFavById($cli_id){
        $list = array();
        $st = db()->prepare("SELECT * FROM t_j_favori_fav WHERE cli_id =:cli_id");
        $st->bindValue(":cli_id", $cli_id);
        $st->execute();
        if ($st->rowCount() > 0){
            foreach($st as $row){
                $list[] = $row;
            }
        }
        return $list;
    }

    public static function isFav($jeu_id){
        if(isset($_SESSION["user"])){
            $st = db()->prepare("SELECT * FROM t_j_favori_fav WHERE cli_id =:cli_id AND jeu_id =:jeu_id");
            $st->bindValue(":cli_id", $_SESSION["user"]->cli_id);
            $st->bindValue(":jeu_id", $jeu_id);
            $st->execute();
            if ($st->rowCount() == 1)
                $retour = true;
            else $retour = false;
        }
        else $retour = false;
        return $retour;
    }

    public static function delfav($jeu_id){
        if(isset($_SESSION["user"])){
            $st = db()->prepare("DELETE FROM t_j_favori_fav WHERE cli_id =:cli_id AND jeu_id =:jeu_id");
            $st->bindValue(":cli_id", $_SESSION["user"]->cli_id);
            $st->bindValue(":jeu_id", $jeu_id);
            $st->execute();
            $retour = true;
        }
        else $retour = false;
        return $retour;
    }
}