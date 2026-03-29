<?php
  require_once("templates/header.php");
?>
  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
      <div class="row" id="auth-row">

        <div class="col-md-4" id="login-container">
          <h2>Entrar</h2>
          <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
            <input type="hidden" name="type" value="login">
            <div class="form-group">
              <label for="login-email">E-mail:</label>
              <input type="email" class="form-control" id="login-email" name="email" placeholder="Digite seu e-mail">
            </div>
            <div class="form-group">
              <label for="login-password">Senha:</label>
              <input type="password" class="form-control" id="login-password" name="password" placeholder="Digite sua senha">
            </div>
            <input type="submit" class="btn card-btn" value="Entrar">
          </form>
        </div>

        <div class="col-md-4" id="register-container">
          <h2>Criar Conta</h2>
          <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
            <input type="hidden" name="type" value="register">
            <div class="form-group">
              <label for="register-email">E-mail:</label>
              <input type="email" class="form-control" id="register-email" name="email" placeholder="Digite seu e-mail">
            </div>
            <div class="form-group">
              <label for="register-first_name">Nome:</label>
              <input type="text" class="form-control" id="register-first_name" name="first_name" placeholder="Digite seu nome">
            </div>
            <div class="form-group">
              <label for="register-last_name">Sobrenome:</label>
              <input type="text" class="form-control" id="register-last_name" name="last_name" placeholder="Digite seu sobrenome">
            </div>
            <div class="form-group">
              <label for="register-password">Senha:</label>
              <input type="password" class="form-control" id="register-password" name="password" placeholder="Digite sua senha">
            </div>
            <div class="form-group">
              <label for="register-confirmpassword">Confirmação de senha:</label>
              <input type="password" class="form-control" id="register-confirmpassword" name="confirmpassword" placeholder="Confirme sua senha">
            </div>
            <input type="submit" class="btn card-btn" value="Registrar">
          </form>
        </div>

      </div>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>