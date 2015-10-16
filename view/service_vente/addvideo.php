<h1>Service vente</h1>
<div class="row">
  <div class="col-md-3">
    <ul class="nav nav-pills nav-stacked">
    <li role="presentation"><a href="./?r=srv/addphoto">Ajouter une photo</a></li>
    <li role="presentation"><a href="./?r=srv/addvideo">Ajouter une video</a></li>
    <li role="presentation"><a href="./?r=srv/gallery">Voir la galerie</a></li>
  </ul>
  </div>
  <div class="col-md-9">
    <form enctype="multipart/form-data" method="post" >
      <div class="form-group">
        <label for="jeu">Jeux</label>
        <select name="jeu" class="form-control" >
          <?php foreach ($data as $jeu) {
            echo '<option value="'.$jeu->jeu_id.'" >'.$jeu->jeu_nom.'</option>';
          } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="InputFile">Url</label>
        <input name="input" class="form-control" type="text" id="Input" >
        <p class="help-block">Site acceptés: youtube</p>
      </div>
      <button type="submit" class="btn btn-primary">Ajouter une video</button>
    </form>
  </div>
</div>