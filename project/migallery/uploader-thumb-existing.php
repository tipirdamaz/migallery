              <!-- existing files -->
              <div id="existingFiles" class="uploaded__files row d-none ml-auto ms-auto mr-auto me-auto mb-2 w-100 border">

<?php 
for ($i=0; $i<count($_SESSION['imgs_info']) && $i<$config['max_upload_limit']; $i++)
{
      $full_img = sprintf(IMG_DIR, $gal_id).'/'.$_SESSION['imgs_info'][$i]->name.".".$_SESSION['imgs_info'][$i]->ext;
      if (file_exists($full_img)) {
         $full_img_file_size = MIGallery::formatBytes(filesize($full_img));
      } else {
         $full_img_file_size = 0;
	  }

      $tn_img = sprintf(IMG_THUMB_DIR, $gal_id).'/'.$_SESSION['imgs_info'][$i]->name.".".$_SESSION['imgs_info'][$i]->ext;
      if (!file_exists($tn_img)) {
         $tn_img = GALLERY_URL.'/no-photo.png';
	  }
?>

                <div class="col thumb thumb_exist" id="exist_<?php echo $_SESSION['imgs_info'][$i]->name.'_'.$_SESSION['imgs_info'][$i]->ext;?>" data-id="<?php echo $_SESSION['imgs_info'][$i]->name.substr($sessid,0,14).time();?>" data-fileapi-id="<?php echo $_SESSION['imgs_info'][$i]->name.substr($sessid,0,14).time();?>">
                  <div class="card my-3"><!-- card -->

                    <div class="card-top row w-100 ml-auto ms-auto mr-auto me-auto">
                      <div class="img-number" id="img_number_<?php echo $_SESSION['imgs_info'][$i]->name;?>"><?php echo Translator::convertNumToLang($i+1);?></div>
                      <div class="thumb__del del_existing" id="del_exist_<?php echo $_SESSION['imgs_info'][$i]->name.'_'.$_SESSION['imgs_info'][$i]->ext;?>" onclick="Uploader.imageDelExisting(this)" title="<?php echo $lang->uploader->uploader->thumb->exist->delete;?>"></div>
                    </div>

                    <div class="thumb-top-bar row w-100 ml-auto ms-auto mr-auto me-auto"></div>

                    <div class="card-img-body">
                      <div class="move_thumb"></div>
                      <div class="thumb__preview" title="<?php echo $lang->uploader->uploader->thumb->move;?>">
                        <canvas id="canvas<?php echo $_SESSION['imgs_info'][$i]->name;?>" width="130" height="130" style='background-image:url("<?php echo MIGallery::getCanvas($tn_img, 130, 130);?>")'>
</canvas>
                      </div>
                    </div>
                    <div class="card-body thumb_exist_bg thumb_unselected">

                  <div class="thumb-bottom-bar row w-100 ml-auto ms-auto mr-auto me-auto">

                    <div class="col-4 p-0">
                      <input id="<?php echo $_SESSION['imgs_info'][$i]->name.'_'.$_SESSION['imgs_info'][$i]->ext;?>" type='checkbox' value="<?php echo $_SESSION['imgs_info'][$i]->name.'.'.$_SESSION['imgs_info'][$i]->ext;?>" class="chkImg filled-in">
                      <label for="<?php echo $_SESSION['imgs_info'][$i]->name.'_'.$_SESSION['imgs_info'][$i]->ext;?>"></label>
                    </div>

                    <div class="col-4 p-0">
                    </div>

                    <div class="col-4 p-0 d-flex justify-content-end">
                      <div class="b-thumb__info" title="<?php echo $lang->uploader->uploader->thumb->exist->info;?>" onclick="Uploader.imageInfoShow('<?php echo $_SESSION['imgs_info'][$i]->name;?>', 'exist')"></div>
                    </div>
               	
                  </div>

                  <div class="row w-100 ml-auto ms-auto mr-auto me-auto">
                    <div class="col-12 p-0">
                      <div class="thumb__name" id="file<?php echo $_SESSION['imgs_info'][$i]->name;?>">
                        <?php echo $_SESSION['imgs_info'][$i]->name.'.'.$_SESSION['imgs_info'][$i]->ext;?>
                      </div>
                    </div>
                  </div>

                    </div>
                  </div><!-- card -->
                  <input type="hidden" id="size<?php echo $_SESSION['imgs_info'][$i]->name;?>" value="<?php echo $full_img_file_size;?>">
                  <input type="hidden" id="title<?php echo $_SESSION['imgs_info'][$i]->name;?>" value="<?php echo htmlentities($_SESSION['imgs_info'][$i]->title, ENT_QUOTES, 'UTF-8');?>">
                  <input type="hidden" id="desc<?php echo $_SESSION['imgs_info'][$i]->name;?>" value="<?php echo htmlentities($_SESSION['imgs_info'][$i]->desc, ENT_QUOTES, 'UTF-8');?>">
                  <input type="hidden" id="keyw<?php echo $_SESSION['imgs_info'][$i]->name;?>" value="<?php echo htmlentities($_SESSION['imgs_info'][$i]->keyw, ENT_QUOTES, 'UTF-8');?>">
                  <input type="hidden" id="time<?php echo $_SESSION['imgs_info'][$i]->name;?>" value="<?php echo date($date_format, $_SESSION['imgs_info'][$i]->time);?>">
                </div>

<?php 
}
?>

              </div>
              <!--/ existing files -->
