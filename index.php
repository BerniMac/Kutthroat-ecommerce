<?php
 require_once 'core/init.php';
 include 'includes/head.php';
 include 'includes/navigation.php';
 include 'includes/headerfull.php';

 $sql = "SELECT * FROM products WHERE featured = 1 ";
 $featured = $DBConnect->query($sql);
?>

<div class="container my-5">
  <div class="text-center mb-5">
    <h2 class="display-6 fw-bold">Featured Products</h2>
    <div style="width: 60px; height: 3px; background: #800000; margin: 10px auto;"></div>
  </div>

  <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    <?php while($product = mysqli_fetch_assoc($featured)): ?>
      <div class="col">
        <div class="card product-card h-100">
          <img src="<?= $product['image']; ?>" class="card-img-top" alt="<?= $product['title']; ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= $product['title']; ?></h5>
            
            <div class="mt-auto">
              <p class="list-price mb-0">List: <s>R<?= $product['list_price']; ?></s></p>
              <p class="price-tag mb-3">Price: R<?= $product['price']; ?></p>
              <button class="btn btn-outline-danger w-100 rounded-pill" onclick="detailsmodal(<?= $product['id'];?>)">
                View Details
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> -->

<script>
function detailsmodal(id){
  var data = {"id" : id};
  $.ajax({
    url: 'includes/detailsmodal.php',
    method: 'post',
    data: data,
    success: function(data){
      // Remove any existing modals to prevent duplication
      $('#details-1').remove();
      $('body').append(data);
      
      // Initialize Bootstrap 5 Modal
      var myModal = new bootstrap.Modal(document.getElementById('details-1'));
      myModal.show();
    },
    error: function(){
      alert('Something went wrong loading product details.');
    }
  });
}

function closeModal(){
  // Helper to force close and clean up backdrop
  var modalEl = document.getElementById('details-1');
  var modal = bootstrap.Modal.getInstance(modalEl);
  if (modal) {
      modal.hide();
  }
  // Allow time for animation then remove from DOM
  setTimeout(function(){
      $('#details-1').remove();
  }, 500);
}

</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
