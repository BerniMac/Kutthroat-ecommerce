<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/headerpartial.php';

  /* // FIX 1: changed $db to $DBConnect to match your init.php
  if($cart_id != ''){
    $cartQ = $DBConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;
    // FIX 2: initialise tax and grand_total here so they exist
    // even if the foreach loop somehow produces nothing
    $tax = 0;
    $grand_total = 0;
  } */

    /* if($cart_id != ''){
    $cartQ = $DBConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);

    // FIX: if no row was found, treat cart as empty
    if(!$result){
      $cart_id = '';
    } else {
      $items = json_decode($result['items'], true);
      $i = 1;
      $sub_total = 0;
      $item_count = 0;
      $tax = 0;
      $grand_total = 0;
    }
  } */

    // FIX: initialise these unconditionally so they always exist
  $i = 1;
  $sub_total = 0;
  $item_count = 0;
  $tax = 0;
  $grand_total = 0;
  $items = [];

  if($cart_id != ''){
    $cartQ = $DBConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);

    if(!$result){
      $cart_id = '';
    } else {
      $items = json_decode($result['items'], true);
    }
  }
?>

<div class="container my-4">
  <div class="row">
    <h2 class="text-center">My Shopping Cart</h2><hr>

    <?php if($cart_id == ''): ?>
      <div class="alert alert-danger text-center">
        Your shopping cart is empty!
      </div>

    <?php else: ?>
      <table class="table table-bordered table-condensed table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $item): ?>
            <?php
              $product_id = $item['id'];
              $productQ = $DBConnect->query("SELECT * FROM products WHERE id = '{$product_id}'");
              $product = mysqli_fetch_assoc($productQ);

              // FIX 3: initialise $available to 0 before the inner loop
              // so it is never undefined if the size isn't matched
              $available = 0;
              $sArray = explode(',', $product['sizes']);
              foreach($sArray as $sizeString){
                $s = explode(':', $sizeString);
                if(isset($s[0]) && $s[0] == $item['size']){
                  $available = (int)$s[1];
                }
              }
            ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$product['title'];?></td>
              <td><?=money($product['price']);?></td>
              <td>
                <button class="btn btn-xs btn-default"
                  onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');">-</button>
                <?=$item['quantity'];?>
                <?php if($item['quantity'] < $available): ?>
                  <button class="btn btn-xs btn-default"
                    onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');">+</button>
                <?php else: ?>
                  <span class="text-danger">Max</span>
                <?php endif; ?>
              </td>
              <td><?=$item['size'];?></td>
              <td><?=money($item['quantity'] * $product['price']);?></td>
            </tr>
            <?php
              $i++;
              $item_count += $item['quantity'];
              $sub_total += ($product['price'] * $item['quantity']);
            ?>
          <?php endforeach; ?>

          <?php
            // FIX 4: calculate tax AFTER the loop using the TAXRATE constant
            // keeping $tax as a float for grand_total math,
            // only format it for display via money()
            $tax = TAXRATE * $sub_total;
            $grand_total = $tax + $sub_total;
          ?>
        </tbody>
      </table>

      <!-- Totals table -->
      <table class="table table-bordered table-condensed text-right">
        <caption><strong>Totals</strong></caption>
        <thead>
          <tr>
            <th>Total Items</th>
            <th>Sub Total</th>
            <th>Tax (VAT <?=TAXRATE*100;?>%)</th>
            <th>Grand Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?=$item_count;?></td>
            <td><?=money($sub_total);?></td>
            <td><?=money($tax);?></td>
            <td class="bg-success"><?=money($grand_total);?></td>
          </tr>
        </tbody>
      </table>

      <!-- FIX 5: Bootstrap 5 uses data-bs-toggle and data-bs-target
           your original used Bootstrap 3 data-toggle/data-target -->
      <button type="button" class="btn btn-primary float-end"
        data-bs-toggle="modal" data-bs-target="#checkoutModal">
        <i class="fas fa-shopping-cart"></i> Check Out &raquo;
      </button>

      <!-- Checkout Modal -->
      <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <!-- FIX 6: Bootstrap 5 close button syntax -->
              <h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="thankYou.php" method="post" id="payment-form">
                <div class="alert alert-danger d-none" id="payment-errors"></div>

                <!-- Hidden fields -->
                <!-- FIX 7: pass raw numbers not formatted strings to thankYou.php -->
                <input type="hidden" name="tax"         value="<?=$tax;?>">
                <input type="hidden" name="sub_total"   value="<?=$sub_total;?>">
                <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                <input type="hidden" name="cart_id"     value="<?=$cart_id;?>">
                <input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from KTB Kutthroat.';?>">

                <!-- Step 1: Shipping Address -->
                <div id="step1">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Full Name:</label>
                      <input class="form-control" id="full_name" name="full_name" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email:</label>
                      <input class="form-control" id="email" name="email" type="email">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Street Address:</label>
                      <input class="form-control" id="street" name="street" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Street Address 2:</label>
                      <input class="form-control" id="street2" name="street2" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">City:</label>
                      <input class="form-control" id="city" name="city" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Province:</label>
                      <input class="form-control" id="state" name="state" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Postal Code:</label>
                      <input class="form-control" id="zip_code" name="zip_code" type="text">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Country:</label>
                      <input class="form-control" id="country" name="country" type="text" value="South Africa">
                    </div>
                  </div>
                </div>

                <!-- Step 2: Card Details -->
                <div id="step2" style="display:none;">
                  <div class="row g-3">
                    <div class="col-md-3">
                      <label class="form-label">Name on Card:</label>
                      <input type="text" id="name" class="form-control" data-stripe="name">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Card Number:</label>
                      <input type="text" id="number" class="form-control" data-stripe="number">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">CVC:</label>
                      <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Expire Month:</label>
                      <select id="exp-month" class="form-select" data-stripe="exp_month">
                        <option value=""></option>
                        <?php for($m=1; $m<13; $m++): ?>
                          <option value="<?=$m;?>"><?=$m;?></option>
                        <?php endfor; ?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Expire Year:</label>
                      <select id="exp-year" class="form-select" data-stripe="exp_year">
                        <option value=""></option>
                        <?php $yr = date("Y"); for($y=0; $y<11; $y++): ?>
                          <option value="<?=$yr+$y;?>"><?=$yr+$y;?></option>
                        <?php endfor; ?>
                      </select>
                    </div>
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;">&laquo; Back</button>
              <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next &raquo;</button>
              <button type="submit" class="btn btn-success" form="payment-form" id="checkout_button" style="display:none;">Check Out &raquo;</button>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>
  </div>
</div>

<script>
  function back_address(){
    document.getElementById('payment-errors').classList.add('d-none');
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('next_button').style.display = 'inline-block';
    document.getElementById('back_button').style.display = 'none';
    document.getElementById('checkout_button').style.display = 'none';
    document.getElementById('checkoutModalLabel').innerHTML = 'Shipping Address';
  }

  function check_address(){
    var data = {
      'full_name' : jQuery('#full_name').val(),
      'email'     : jQuery('#email').val(),
      'street'    : jQuery('#street').val(),
      'street2'   : jQuery('#street2').val(),
      'city'      : jQuery('#city').val(),
      'state'     : jQuery('#state').val(),
      'zip_code'  : jQuery('#zip_code').val(),
      'country'   : jQuery('#country').val(),
    };
    jQuery.ajax({
      // FIX 8: corrected path to match your actual file structure
      url    : 'check_address.php',
      method : 'POST',
      data   : data,
      success: function(response){
        if(response != 'passed'){
          jQuery('#payment-errors').removeClass('d-none').html(response);
        } else {
          jQuery('#payment-errors').addClass('d-none').html('');
          document.getElementById('step1').style.display = 'none';
          document.getElementById('step2').style.display = 'block';
          document.getElementById('next_button').style.display = 'none';
          document.getElementById('back_button').style.display = 'inline-block';
          document.getElementById('checkout_button').style.display = 'inline-block';
          document.getElementById('checkoutModalLabel').innerHTML = 'Enter Your Card Details';
        }
      },
      error: function(){ alert('Something went wrong. Please try again.'); }
    });
  }

  Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

  function stripeResponseHandler(status, response){
    var $form = $('#payment-form');
    if(response.error){
      $('#payment-errors').removeClass('d-none').text(response.error.message);
      $form.find('button').prop('disabled', false);
    } else {
      $form.append($('<input type="hidden" name="stripeToken">').val(response.id));
      $form.get(0).submit();
    }
  }

  jQuery(function($){
    $('#payment-form').submit(function(e){
      e.preventDefault();
      $(this).find('button').prop('disabled', true);
      Stripe.card.createToken($(this), stripeResponseHandler);
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
