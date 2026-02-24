 
 <?php
// Keep SQL Logic exact
$sql = "SELECT * FROM catergories WHERE parent = 0";
$pquery = $DBConnect->query($sql);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
  <div class="container">
    <a href="tutorial/index.php" class="navbar-brand">KTB Kutthroat Amunition</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php while($parent = mysqli_fetch_assoc($pquery)): ?>
          <?php
            $parent_id = $parent['id'];
            $sql2 = "SELECT * FROM catergories WHERE parent = '$parent_id'";
            $cquery = $DBConnect->query($sql2);
          ?>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <?= $parent['catergory']; ?>
            </a>
            <ul class="dropdown-menu">
              <?php while($child = mysqli_fetch_assoc($cquery)): ?>
                <li><a class="dropdown-item" href="#"><?= $child['catergory']; ?></a></li>
              <?php endwhile; ?>
            </ul>
          </li>
        <?php endwhile; ?>
        
        <li class="nav-item">
          <a href="cart.php" class="btn btn-dark ms-2 rounded-pill">
            <i class="fas fa-shopping-cart"></i> My Cart
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>