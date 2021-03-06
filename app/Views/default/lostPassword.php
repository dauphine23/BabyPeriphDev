<?php $this->layout('layout', ['title' => 'mot de passe perdu']) ?>

<?php $this->start('main_content') ?>
<?php if(isset($_SESSION['erreur'])){
  echo '<h1>' . $_SESSION['erreur'] . '</h1>';
  unset($_SESSION['success']);
} ?>
<form method="post" action="<?= $this->url('default_traitementLost') ?>">  
  <div class="row">
    <div class="col-md-offset-4 col-md-4">
	  <div class="form-group">
		<label for="mail">Adresse mail</label>
		<input type="email" name="email" id="mail" class="form-control" required />
  	  </div>
  	  <div class="form-group text-center">
    	<input type="submit" name="btnSub" value="Envoyer" 
    	 	   class="btn btn-lg btn-success" />
  	  </div>
  	</div>
  </div>
</form>
<?php $this->stop('main_content') ?>