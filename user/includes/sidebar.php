<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">

  <div class="main-wrapper">

    <div class="header header-one">

      <div class="header-left header-left-one">
        <a href="index.php" class="white-logo">
          <img src="../assets/img/logo-white.png" alt="Logo">
        </a>
        <a href="index.php" class="white-logo">
          <img src="../assets/img/logo-white.png" alt="Logo">
        </a>
        <a href="index.php" class="white-logo">
          <img src="../assets/img/logo-white.png" alt="Logo">
        </a>

      </div>


      <a href="javascript:void(0);" id="toggle_btn">
        <i class="fas fa-bars"></i>
      </a>


      <div class="top-nav-search">
        <form>
          <input type="text" class="form-control" placeholder="Search here">
          <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </form>
      </div>


      <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
      </a>
      <ul class="nav nav-tabs user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
          <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <span class="user-img">
              <img src="../assets/img/profiles/images.png" alt="">
              <span class="status online"></span>
            </span>
            <span><?php echo  $_SESSION['name']; ?></span>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="profile.php"><i data-feather="user" class="me-1"></i> Profile</a>
            <a class="dropdown-item" href="logout.php"><i data-feather="log-out" class="me-1"></i> Logout</a>
          </div>
        </li>

      </ul>

    </div>
    <div class="sidebar" id="sidebar">
      <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
          <ul>
            <li class="menu-title"><span>Main</span></li>
            <li>
              <a href="index.php"><i data-feather="home"></i> <span>Dashboard</span></a>
            </li>
            <li>
              <a href="all_customers.php"><i data-feather="users"></i> <span>Macaamiil</span></a>
            </li>
            <li>
              <a href="expenses.php"><i data-feather="file-text"></i> <span>Dhakhli / Kharash </span></a>
            </li>

          </ul>
        </div>
      </div>
    </div>