 <!-- Modal For Deleting -->
 <div class="modal fade modal-black" id="exampleModal1<?php echo $user_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">
                 Jeste li sigurni da želite obrisati ovog korisnika?
             </div>
             <div class="modal-footer">
                 <?php $_SESSION['prevUrl'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                 <a href="admin-users.php?delete=<?php echo $user_id ?>"><button type="button" class="btn btn-primary">Izbriši</button></a>


             </div>
         </div>
     </div>
 </div>



 <!-- Modal For Admin/Standard -->
 <div class="modal fade modal-black" id="exampleModal3<?php echo $user_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Potvrda promjene ovlasti</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">
                 Jeste li sigurni da želite promjeniti ovlasti ovog korisnika?
             </div>
             <div class="modal-footer">
                 <?php $_SESSION['prevUrl'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                 <a href="admin-users.php?edit=<?php echo $user_id ?>&role=<?php echo $user_role ?>"><button type="button" class="btn btn-primary">Promjena</button></a>


             </div>
         </div>
     </div>
 </div>




 <!-- Modal For Images -->
 <div class="modal fade modal-black" id="exampleModal2<?php echo $user_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog  " role="document">
         <div class=" modal-content">
             <div class=" modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Profilna Slika</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">

                 <div class="card-body">

                     <img src="../userImages/profile.jpg" alt="">
                 </div>

             </div>

         </div>
     </div>
 </div>