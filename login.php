<?php
require_once("common.php");

include_once('auth_ssh.class.php');
$au = new auth_ssh();

if (isset($_GET['action']) && $_GET['action'] == "logout") {
  $au->logout();
}

$flag = false;
if (isset($_GET['error']) && $_GET['error'] == "true") {
  $flag = true;
}

// получение параметров запроса
$page_id = 0;
if (array_key_exists('pageaddr', $_REQUEST))
  $page_addr = $_REQUEST['pageaddr'];
else
  $page_addr = '';

show_head("Страница Авторизации");
?>

<body style="overflow-x: hidden;">
  <?php show_header('АВТОРИЗАЦИЯ'); ?>
  <main class="pt-2">
    <div class="container-fluid overflow-hidden">
      <div class="row gy-5">
        <div class="col-12 col-xl-4"></div>
        <div class="col-12 col-xl-4 pt-4">
          <div class="pt-1 px-3" style="border: 1px solid #dee2e6; border-radius: 5px;">
            <h2 class="my-2">Авторизация</h2>

            <form class="text-nowrap form-horizontal" method="post" action="auth.php">
              <input type="hidden" name="action" value="login" />

              <div class="form-outline my-3">
                <input type="text" id="login" name="login" class="form-control" />
                <label class="form-label" for="login">Логин</label>
              </div>

              <div class="form-outline my-3">
                <input type="password" id="pass" name="password" class="form-control" />
                <label class="form-label" for="pass">Пароль</label>
              </div>

              <?php if ($flag) { ?>
                <strong>
                  <p id="error-authorization" class="error text-danger">ОШИБКА ВХОДА! Неверный Логин или Пароль</p>
                </strong>
                <!-- <strong><p id="error-field-filled" class="error" style="display: none;">Ошибка входа! Незаполненные поля!</p></strong> -->
              <?php } ?>

              <button type="submit" class="btn my-2 col-xl-3">
                <i class="fas fa-signin-alt fa-lg"></i>Войти
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </main>

  <script type="text/javascript">

  </script>

  <?php
  show_footer();
  ?>