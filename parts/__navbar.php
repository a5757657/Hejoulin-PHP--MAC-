    <?php if(isset($_SESSION['admin'])): ?>
    <body>
    
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="../home/index.php">禾酒林</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="w-100 navbar-dark">
      <ul class="navbar-nav px-3 navbar-dark d-flex flex-row gap-3 justify-content-end">
        <li class="nav-item text-nowrap">
          <a class="nav-link"><?= $_SESSION['admin']['account'] ?></a>
        </li>
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="../login/logout.php">登出</a>
        </li>
      </div>
    </header>
    <?php else: ?>
      <body>
    
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">禾酒林</a>
      <div class="navbar-dark">
      <ul class="navbar-nav px-3 navbar-dark">
          <li class="nav-item text-nowrap">
            <a class="nav-link" href="../login/login.php">登入</a>
          </li>   
      </ul>
      </div>
    </header>
    <?php endif; ?>