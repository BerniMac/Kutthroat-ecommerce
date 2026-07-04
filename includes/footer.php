<!-- Footer -->
<div class="footer">
  <footer class="text-center">&copy; 2025 KTB Kutthroat Amunition</footer>
</div>

<!-- FIX: jQuery and Bootstrap JS must load on every page, not just index.php -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
// function detailsmodal(id){
//   var data = {"id" : id};
//   $.ajax({
//     url: '/tutorial/includes/detailsmodal.php',
//     method: 'post',
//     data: data ,
//     success: function(data){
//       $('body').append(data); // inject modal HTML
//       $('#details-1').modal('show'); // show the modal
//     },
//     error: function(){
//       alert('Something went wrong loading product details.');
//     }
//   });
// }

 function update_cart(mode,edit_id,edit_size){
   var data={"mode": mode, "edit_id": edit_id, "edit_size": edit_size};
   jQuery.ajax({
     url:'./update_cart.php',
     method:"post",
     data : data,
     success : function(){location.reload();},
     error:function(){alert("something went wrong.");},

   });
 }
/* 
 function add_cart(){
   jQuery.ajax('#modal_errors').html("");
   var size = jQuery('#size').val();
   var quantity = jQuery('#quantity').val();
   var available = jQuery('#available').val();
   var error = '';
   var data = jQuery('#add_product_form').serialize();
   if(size =='' || quantity == '' || quantity == 0){
     error += '<p class = "text-danger tex-center">you must choose a size and quantity.</p>';
     jQuery('#modal_errors').html(error);
     return;
   }else if(quantity > available){
     error += '<p class="text-danger text-center">there are only '+ available + ' available</p>';
      jQuery('#modal_errors').html(error);
   }else{
     jQuery.ajax({
       url:'add_cart.php',
       method:"post",
       data : data,
       success : function(){location.reload();},
       error:function(){alert("something went wrong.");},

     });
   }
 } */

   function add_cart(){
   jQuery('#modal_errors').html("");   // FIX: removed .ajax(), just clear the errors div
   var size = jQuery('#size').val();
   var quantity = jQuery('#quantity').val();
   var available = jQuery('#available').val();
   var error = '';
   var data = jQuery('#add_product_form').serialize();

   console.log('size:', size, 'quantity:', quantity, 'available:', available); // TEMP debug

   if(size == '' || quantity == '' || quantity == 0){
     error += '<p class="text-danger text-center">you must choose a size and quantity.</p>';
     jQuery('#modal_errors').html(error);
     return;
   } else if(quantity > available){
     error += '<p class="text-danger text-center">there are only '+ available + ' available</p>';
     jQuery('#modal_errors').html(error);
     return; // FIX: also added a return here, your original kept going without it
   } else {
     jQuery.ajax({
       url: 'add_cart.php',
       method: 'post',
       data: data,
       success: function(){ location.reload(); },
       error: function(){ alert('something went wrong.'); },
     });
   }
}


</script>
