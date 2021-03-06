
<link rel="stylesheet" href="public/css/style.css">
<?php


if(is_string($data)){
	echo "<a href='?r=avi/add&id_game=".$data."' ><button type='button' class='btn btn-primary'>Deposer un avis</button></a>";


}
else{
	echo "<a href='?r=avi/add&id_game=".$data[0]->T_E_JEUVIDEO_JEU->jeu_id."' ><button type='button' class='btn btn-primary'>Deposer un avis</button></a>";
	echo "<h2>Avis sur le jeu</h2>";

	echo '<div class="dropdown orderbtn">';
	  	echo '<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Trier par avis <span class="caret"></span></button>';
	  	echo '<ul class="dropdown-menu order /'.$data[0]->T_E_JEUVIDEO_JEU->jeu_id.'" aria-labelledby="dropdownMenu1">';
		    echo '<li><a href="#" class="asc">Croissant</a></li>';
		    echo '<li><a href="#" class="desc">Décroissant</a></li>';
	  	echo '</ul>';
	echo '</div>';

	echo "<div class='avis'>";
		foreach($data as $avi){
			$star = "";
			//créer des étoiles vides
			for ($i = 0; $i <= 5; $i++) {
				$stars[$i] = "<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>";
			}
			//remplis les étoiles vides selon la note
			for ($i = 0; $i < $avi->avi_note; $i++) {
				$stars[$i] = "<span class='glyphicon glyphicon glyphicon-star' aria-hidden='true'></span>";
			}
			for ($i = 0; $i < 5; $i++) {
				$star .= $stars[$i];
			}	
			echo "<div class='panel panel-default'>";
		    	echo "<div class='panel-heading'><strong>".$avi->avi_titre."</strong> : ".$star." - <span class='text-capitalize'><a href='?r=cli/viewOne&id_cli=".$avi->T_E_CLIENT_CLI->cli_id."'>".$avi->T_E_CLIENT_CLI->cli_pseudo."</a>	</span> <small>(".date("d/m/Y", strtotime($avi->avi_date)).")</small><small><div class='pull-right'><a href='?r=avi/signal&id_avi=".$avi->avi_id."'>Signaler ce commentaire comme abusif</a></div></small></div>";
		    	echo "<div class='panel-body'>".$avi->avi_detail."</div>";
		    	echo '<a class="btn btn-default reco like /'.$avi->avi_id.'">
	    			<span class="text-success glyphicon glyphicon-thumbs-up"></span> '.T_J_AVISRECOMMANDE_AVR::getAvisRecommande($avi->avi_id).'</a>';
	    		echo '<a class="btn btn-default reco dislike /'.$avi->avi_id.'">
	    			<span class="text-danger glyphicon glyphicon-thumbs-down"></span> '.T_J_AVISDECONSEILLE_AVD::getAvisDecons($avi->avi_id).'</a>';
	  		echo "</div>";
	  	}
  	echo "</div>";
 }

?>
 <script type="text/javascript" src="public/js/avis.js"></script>