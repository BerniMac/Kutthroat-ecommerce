<?php
require_once '../core/init.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id === 0) die("Invalid product ID.");

$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $DBConnect->query($sql);
$product = mysqli_fetch_assoc($result);

if (!$product) die("Product not found.");

$brand_id = $product['brand'];
$sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
$brand_query = $DBConnect->query($sql);
$brand = mysqli_fetch_assoc($brand_query);

$sizestring = isset($product['sizes']) ? $product['sizes'] : '';
$sizestring = rtrim($sizestring, ',');
$size_array = explode(',', $sizestring);

ob_start();
?>

<div class="modal fade" id="details-1" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $product['title']; ?></h5>
        <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="fotorama" data-autoplay="true" data-width="100%" data-ratio="4/3">
                <?php $photos = explode(';', $product['image']); ?>
                <?php foreach ($photos as $photo): ?>
                  <img src="<?= $photo; ?>" alt="<?= $product['title']; ?>">
                <?php endforeach; ?>
              </div>
            </div>

            <div class="col-md-6">
              <h4>Details</h4>
              <p><?= nl2br($product['description']); ?></p>
              <hr>
              <h3 class="text-danger">R<?= $product['price']; ?></h3>
              <p class="text-muted">Brand: <?= $brand['brand']; ?></p>

              <form action="add_cart.php" method="post" id="add_product_form">
                <input type="hidden" name="product_id" value="<?= $id; ?>">
                <input type="hidden" name="available" id="available">

                <div class="row mb-3">
                  <div class="col-6">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
                  </div>
                  <div class="col-6">
                    <label for="size" class="form-label">Size:</label>
                    <select name="size" id="size" class="form-select">
                      <option value="">Select Size</option>
                      <?php foreach ($size_array as $string):
                        $string_array = explode(':', $string);
                        $size = $string_array[0];
                        $available = $string_array[1];
                        if ($available > 0): ?>
                          <option value="<?= $size; ?>" data-available="<?= $available; ?>">
                            <?= $size; ?> (<?= $available; ?>)
                          </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div id="modal_errors"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
         <!-- Make the button actually validate before submitting. -->
        <button class="btn btn-danger" type="button" onclick="add_cart();">
          <i class="fas fa-shopping-cart"></i> Add to cart
        </button>

        <!--   <button class="btn btn-danger" type="submit" form="add_product_form">
          <i class="fas fa-shopping-cart"></i> Add to cart -->

        </button>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    // Re-initialize Fotorama if needed inside modal
    $('.fotorama').fotorama();

    $('#size').change(function() {
      var available = $('#size option:selected').data("available");
      $('#available').val(available);
    });
  });
</script>

<?php echo ob_get_clean(); ?>