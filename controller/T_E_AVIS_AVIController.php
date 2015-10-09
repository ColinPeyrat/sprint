<?php 

class T_E_AVIS_AVIController extends Controller
{
	public function findByGame(){
        //echo $_SERVER['REQUEST_URI'];
	    if(isset($_GET["id_game"])){
	    	$id_game = $_GET["id_game"];
            $data = T_E_AVIS_AVI::findByGame($id_game);
            if(count($data)>0){
	    	  $this->render("displayByGame", $data);
            } else {
                $m = new message();
                $m->setFlash("Aucun avis pour ce jeu.","warning");
                header("Refresh:0; url=?r=jeu");
            }
	    }
	}


	public function add(){
        $m = new message();
    	if(isset($_POST["addbtn"]) && $_POST["addbtn"] == "Ajouter"){

            $avi = new T_E_AVIS_AVI();
            $client = new T_E_CLIENT_CLI($_GET['id_client']);
            $jeu = new T_E_JEUVIDEO_JEU($_GET['id_game']);

            $avi->__set("T_E_CLIENT_CLI",$client);
            $avi->__set("T_E_JEUVIDEO_JEU",$jeu);
            $avi->__set("avi_titre",$_POST["titre"]);
            $avi->__set("avi_detail",$_POST["detail"]);
            $avi->__set("avi_note",$_POST["note"]);

            $avi->add();
            $m->setFlash($client->cli_prenom.", votre avis a bien été déposé.","success");
            $this->render("displayByGame",T_E_AVIS_AVI::findByGame($jeu->jeu_id));
    	}
    	else{
    		$this->render("add");
    	}
    }

	public function signal(){
		if(isset($_GET['id_avi'])){
			if(!isset($_SESSION['user'])){
				$m = new message();
				$m->setFlash("Vous devez etre connecté pour signaler un commentaire");
				$c = new T_E_CLIENT_CLIController();
				$c->render("login");
			} else {
//				$ava = new T_J_AVISABUSIF_AVA($_GET['id_avi'], $_SESSION['user']->cli_id);
//				$idAvaAuthor = $ava->T_E_AVIS_AVI->T_E_CLIENT_CLI->cli_id;
//                var_dump($idAvaAuthor);
//                var_dump($_SESSION['user']->cli_id);
                $allAvas = new T_J_AVISABUSIF_AVA(1);
                var_dump($allAvas);
//                foreach ($allAvas as $ava) {
////                    if($ava->T_E_AVIS_AVI->avi_id == $_GET['id_avi']) {
//                            var_dump($ava->T_E_AVIS_AVI->T_E_CLIENT_CLI);
////                            var_dump($ava->T_E_AVIS_AVI->T_E_CLIENT_CLI->cli_id);
////                    }
//
//                }

                if($idAvaAuthor == $_SESSION['user']->cli_id){
					$m = new message();
					$m->setFlash("Vous avez déja signaler ce commentaire, merci d'attendre que le service vente prenne en compte votre requête");
                    $c = new T_E_JEUVIDEO_JEUController();

                    //renvois a la liste de tous les jeux
                    $j = new T_J_JEURAYON_JER();
                    $games = $j->findGameByRay('tous', "");
                    $c->render("index", [$games, T_R_RAYON_RAY::findall()]);
				} else {
					$ava = new T_J_AVISABUSIF_AVA(null,$_SESSION['user']->cli_id,$_GET['id_avi']);
                    $m = new message();
                    $m->setFlash("Le commentaire a bien été signalé",'success');
                    $c = new T_E_JEUVIDEO_JEUController();
                    //renvois a la liste de tous les jeux

                    $j = new T_J_JEURAYON_JER();
                    $games = $j->findGameByRay('tous', "");
                    $c->render("index", [$games, T_R_RAYON_RAY::findall()]);
                }
			}


		}
	}
}