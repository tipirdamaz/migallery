<?php 

   /**
   * View images of gallery for all users
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn from mi-init.php
   * @var {Array} $config from CONFIG_FILE
   * @var {String} $protocol from DEFINITIONS_FILE
   * 
   * $_GET vars
   * @var {Integer} $_GET['gal_id'] gallery id
   * 
   */

require_once('mi-session.php');
include_once('migallery/definitions0.php');

if (!file_exists(CONFIG_FILE)) {
   echo 'Configuration file not found. Please reinstall MIGallery.';
   exit;
}

if (!file_exists(DEFINITIONS_FILE)) {
   echo 'Definitions file not found. Please reinstall MIGallery.';
   exit;
}

require_once(GALLERY_DIR.'/Translator.class.php');
include_once(DEFINITIONS_FILE);
require_once(CONFIG_FILE);
date_default_timezone_set($config['timezone']);
require_once('mi-init.php');
require_once(GALLERY_DIR.'/MIGallery.class.php');
Translator::select($config['lang']);
$langCode = Translator::getLangCode();
$langDir = Translator::getDirection();
$langNums = Translator::getLangNumbers();
$lang = Translator::translate();
$date_format = Translator::getLocaleDateFormat().' H:i';

$gal_id = $_GET['gal_id'];

MIGallery::init($config, $db_conn, $gal_id);
$gallery = MIGallery::getGalleryAndImageInfo();
   
$gal_name = $gallery['name'];
$gal_desc = $gallery['desc'];
$gal_keyw = $gallery['keyw'];
$gal_date = date($date_format, intval($gallery['time']));
$gal_imgs = $gallery['imgs'];

$gal_name = htmlentities($gal_name, ENT_QUOTES, 'UTF-8');
$gal_desc = htmlentities($gal_desc, ENT_QUOTES, 'UTF-8');
$gal_keyw = htmlentities($gal_keyw, ENT_QUOTES, 'UTF-8');

$img_count = count($gal_imgs);


/* for open graph */
$title_arr = explode(" ", preg_replace('/\s{2,}/', ' ', preg_replace('/[\"]+/', '', $gal_name)));
$og_title = "";
for ($i=0; mb_strlen($og_title . $title_arr[$i].' ', 'UTF-8') <= 35 && $i < count($title_arr); $i++) {
   $og_title .= $title_arr[$i].' ';
}
$desc_arr = explode(" ", preg_replace('/\s{2,}/', ' ', preg_replace('/[\"]+/', '', $gal_desc)));
$og_description = "";
for ($i=0; mb_strlen($og_description . $desc_arr[$i].' ', 'UTF-8') <= 65 && $i < count($desc_arr); $i++) {
   $og_description .= $desc_arr[$i].' ';
}

if ($img_count > 0) {
   $og_image = sprintf(IMG_THUMB_C_URL, $gal_id).'/'.$gal_imgs[0]['file'];
   $og_image_w = $gal_imgs[0]['thumb_c_width'];
   $og_image_h = $gal_imgs[0]['thumb_c_height'];
} else {
   $og_image = GALLERY_URL.'/no-photo.png';
   $og_image_w = '1024';
   $og_image_h = '768';
}
$og_image_secure = preg_replace("/^http:/i", "https:", $og_image);

$share_page_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $gal_name;?></title>
<meta name="keywords" content="<?php echo $gal_keyw;?>" />
<meta name="description" content="<?php echo $gal_desc;?>" />
<meta name="dcterms.rights" content="Multi Image Uploader" />
<meta name="dcterms.audience" content="Global" />
<meta property="og:locale" content="<?php echo $langCode;?>" />
<meta property="og:site_name" content="<?php echo $_SERVER['SERVER_NAME'];?>" />
<meta property="og:title" content="<?php echo $og_title;?>" />
<meta property="og:description" content="<?php echo $og_description;?>" />
<meta property="og:url" content="<?php echo $protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" />
<meta property="og:type" content="product" />
<meta property="og:image" itemprop="image" content="<?php echo $og_image;?>" />
<meta property="og:image:secure_url" itemprop="image" content="<?php echo $og_image_secure;?>" />
<meta property="og:image:width" content="<?php echo $og_image_w;?>" />
<meta property="og:image:height" content="<?php echo $og_image_h;?>" />

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the css files according to the version you use. -->

<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs4.css" rel="stylesheet" type="text/css"/>
<!--
<link href="bootstrap-5.0.0-beta2/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs5.css" rel="stylesheet" type="text/css"/>
-->

<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/plugins/baguetteBox/css/baguetteBox.min.css" rel="stylesheet" />
<link href="<?php echo GALLERY_URL;?>/css/gallery-view.css" rel="stylesheet" type="text/css"/>
<?php if ($langDir == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/migallery-rtl.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/gallery-view-rtl.css" rel="stylesheet" type="text/css"/>
<?php if ($config['img_direction'] == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/gallery-view-img-rtl.css" rel="stylesheet" type="text/css"/>
<?php }?>
<?php }?>
</head>
<body>

<?php require_once('mi-navbar.php');?>

<div class="container wiew-page gallery-block compact-gallery"><!-- page container -->

  <div class="row d-flex justify-content-center mt-4 pt-3"><!-- page row -->

    <div class="col-12" itemscope itemtype="http://schema.org/ImageGallery"><!-- page col -->

      <div class="heading border-bottom"><!-- page header -->
        <h2 itemprop="name"><?php echo nl2br($gal_name);?></h2>
        <p class="mb-1" itemprop="description"><?php echo nl2br($gal_desc);?></p>
        <p class="mb-2">

<?php 
          $keywords = explode(',', $gal_keyw);
          foreach ($keywords as $kw) {
            echo "<span class='badge badge-info bg-info gal-keyword'>$kw</span>";
          }
?>        

        </p>
        <meta itemprop="keywords" content="<?php echo $gal_keyw;?>">
        <p class="text-secondary"><?php echo $gal_date;?></p>
        <meta itemprop="datePublished" content="<?php echo date("Y-m-d", intval($gallery['time']));?>">
      </div><!--/ page header -->

      <div class="row mx-0 gallery-view-wrapper"><!-- page content row -->

<?php

   for ($i=0; $i<$img_count; $i++) 
   {
      $img_file = $gal_imgs[$i]['file'];
      $img_title = $gal_imgs[$i]['title'];
      $img_desc = $gal_imgs[$i]['desc'];
      $img_keyw = $gal_imgs[$i]['keyw'];

      $img_title = htmlentities($img_title, ENT_QUOTES, 'UTF-8');
      $img_desc = htmlentities($img_desc, ENT_QUOTES, 'UTF-8');
      $img_keyw = htmlentities($img_keyw, ENT_QUOTES, 'UTF-8');

      $full_image_w = $gal_imgs[$i]['full_width'];
      $full_image_h = $gal_imgs[$i]['full_height'];

      $thumb_c_image_w = $gal_imgs[$i]['thumb_c_width'];
      $thumb_c_image_h = $gal_imgs[$i]['thumb_c_height'];

      $keywords = explode(',', $img_keyw);
      $keyw_badge = '';
      foreach ($keywords as $kw) {
         $keyw_badge .= "<span class='badge badge-dark bg-dark img-keyword'>$kw</span>";
      }

      $img_size = MIGallery::formatBytes(intval($gal_imgs[$i]['full_size']));
      $img_date = date($date_format, intval($gal_imgs[$i]['time']));
      $tn_img = sprintf(IMG_THUMB_C_URL, $gal_id).'/'.$img_file;
      $full_img = sprintf(IMG_URL, $gal_id).'/'.$img_file;

      $data_caption = "<div class='img-info'>"
                     ."  <span class='btn-toggle-expand'>"
                     ."    <span class='fa fa-long-arrow-up'></span>"
                     ."  </span>"
                     ."  <div class='img-info-content'>"
                     ."    <h2>".$img_title."</h2>"
                     ."    <p>".$img_desc."</p>"
                     ."    <p>".$keyw_badge."</p>"
                     ."    <p><span class='file-size'>".$lang->gallery_view->filesize.'</span> <span>'.$img_size."</span></p>"
                     ."    <p class='d-inline-flex'><span class='upload-date'>".$lang->gallery_view->filedate.'</span> <span>'.$img_date."</span></p>"
                     ."  </div>"
                     ."</div>";
?>
        <div class="thumb_wiew col-12 col-md-6 col-lg-3 col-xl-3 col-xxl-2 px-0" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
          <div class="view-gal-number"><?php echo Translator::convertNumToLang($i+1);?></div>
          <div class="item zoom-on-hover">
            <a class="lightbox" href="<?php echo $full_img;?>" data-caption="<?php echo $data_caption;?>" itemprop="contentUrl" data-size="<?php echo $full_image_w.'x'.$full_image_h;?>" data-index="<?php echo $i;?>">
              <img class="img-fluid image" src="<?php echo $tn_img;?>" alt="<?php echo $img_title;?>" width="<?php echo $thumb_c_image_w;?>" height="<?php echo $thumb_c_image_h;?>" itemprop="thumbnail">
              <span class="description">
                <span class="description-heading" itemprop="name"><?php echo $img_title;?></span>
                <span class="description-body" itemprop="description"><?php echo $img_desc;?></span>
              </span>
            </a>
            <meta itemprop="keywords" content="<?php echo $img_keyw;?>">
            <meta itemprop="datePublished" content="<?php echo date("Y-m-d", intval($gal_imgs[$i]['time']));?>">
          </div>
        </div>
<?php
   }

   if ($img_count == 0) echo '<div class="col-12"><div class="alert alert-info" role="alert">'.$lang->gallery_view->alert.'</div></div>';

   $result = MIGallery::getResult();
   MIGallery::end();

   if($result['code'] == MIGallery::ERROR) {
      echo '<div class="col-12"><div class="alert alert-danger" role="alert">'.$result['result'].'</div></div>';
   }

?>

      </div><!--/ page content row -->
         
    </div><!--/ page col -->

  </div><!--/ page row -->


  <!-- Share Gallery row -->

  <div class="row ml-auto ms-auto mr-auto me-auto my-3">

    <div class="col-12 pr-0 font-weight-bold fw-bold border-bottom text-right text-end"><?php echo $lang->gallery_view->share;?></div>

    <div class="col-12 py-3 px-0 d-flex flex-wrap justify-content-end">

      <a href="javascript:" title="Copy the link to the page" data-toggle="modal" data-target="#copyLinkModal" rel="nofollow">
        <span class="fa fa-clipboard fa-2x" style="color:#100F0D"></span>
      </a>

      <a class="ml-2 ms-2" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Facebook" rel="nofollow">
        <span class="fa fa-facebook-square fa-2x" style="color:#3D539F"></span>
      </a>

      <a class="ml-2 ms-2" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Twitter" rel="nofollow">
        <span class="fa fa-twitter-square fa-2x" style="color:#52ABE4"></span>
      </a>

      <a class="ml-2 ms-2" href="https://plus.google.com/share?url=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Google Plus" rel="nofollow">
        <span class="fa fa-google-plus-square fa-2x" style="color:#DF3832"></span>
      </a>

      <a class="ml-2 ms-2" href="https://www.tumblr.com/share/photo?source=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Tumblr" rel="nofollow">
        <span class="fa fa-tumblr-square fa-2x" style="color:#314A6C"></span>
      </a>

      <a class="ml-2 ms-2" href="https://pinterest.com/pin/create/button/?media=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Piterest" rel="nofollow">
        <span class="fa fa-pinterest-square fa-2x" style="color:#CD1E28"></span>
      </a>

      <a class="ml-2 ms-2" href="https://reddit.com/submit?url=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Reddit" rel="nofollow">
        <span class="fa fa-reddit-square fa-2x" style="color:#CEE3F8"></span>
      </a>

      <a class="ml-2 ms-2" href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode($share_page_link);?>" target="_blank" title="Share on Linkedin" rel="nofollow">
        <span class="fa fa-linkedin-square fa-2x" style="color:#3479BD"></span>
      </a>

      <a class="d-lg-none ml-2 ms-2" href="whatsapp://send?text=<?php echo urlencode($share_page_link);?>" data-action="share/whatsapp/share" title="Share on Whatsapp" rel="nofollow">
        <span class="fa fa-whatsapp fa-2x" style="color:#1AD741"></span>
      </a>

    </div>
  </div>

  <!--/ Share Gallery row -->


<!-- copy link Modal -->
<div class="modal fade" id="copyLinkModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">
              <?php echo $lang->gallery_view->modal->copy_clipboard->label;?>
            </h5>
            <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->gallery_view->modal->copy_clipboard->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <form class="form-inline">
          <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
            <div class="col-12">
              <textarea class="w-100 form-control bg-white" id="copy-link" name="copy-link" readonly><?php echo $share_page_link;?></textarea>
            </div>
          </div>
          <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
            <div class="col-12">
              <div id="copy-link-msg" class="text-success d-none"><span class="fa fa-check"></span> <?php echo $lang->gallery_view->modal->copy_clipboard->text;?></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
          <div class="col-6">
   <a href="javascript:var cplink = document.getElementById('copy-link');cplink.select();document.execCommand('copy');cplink.blur();$('#copy-link-msg').removeClass('d-none').addClass('d-block')" class="btn btn-info w-75">
     <span class="fa fa-copy mr-2 me-2"></span> <?php echo $lang->gallery_view->modal->copy_clipboard->command;?>
   </a>
          </div>
          <div class="col-6 text-right text-end">
            <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75">
              <?php echo $lang->gallery_view->modal->copy_clipboard->cancel;?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ copy link Modal -->


</div><!--/ page container -->

<script src="js/jquery-3.5.1.min.js"></script>

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the js files according to the version you use. -->

<script src="bootstrap-4.5.3/popper.min.js"></script>
<script src="bootstrap-4.5.3/bootstrap.min.js"></script>
<!--
<script src="bootstrap-5.0.0-beta2/popper.min.js"></script>
<script src="bootstrap-5.0.0-beta2/bootstrap.min.js"></script>
-->

<script src="<?php echo GALLERY_URL;?>/plugins/baguetteBox/js/baguetteBox-changed.js"></script>

<script>

$('#copyLinkModal').on('show.bs.modal', function (e) {
  $('#copy-link-msg').removeClass('d-block').addClass('d-none');
  if (window.getSelection) {window.getSelection().removeAllRanges();}
  else if (document.selection) {document.selection.empty();}
});

   baguetteBox.run('.compact-gallery', { animation: 'slideIn', slideDirection: "<?php echo $langDir == 'rtl' ? ($config['img_direction'] == 'rtl' ? 'rtl' : 'ltr') : 'ltr';?>"});

   $(function(){
      $('.container.wiew-page').css({ minHeight: $(window).innerHeight() - 150 });
      $(window).resize(function(){
         $('.container.wiew-page').css({ minHeight: $(window).innerHeight() -150 });
      });
   });

   var toggleExpand = function() {

      $('body').on('click', '.btn-toggle-expand', function() {
			
         if ( $(this).hasClass('active') ) {
				
            $(this).next('.img-info-content').removeClass('active');
            $(this).next('.img-info-content').css({opacity :'0'});
            $(this).closest('.img-info').animate({
               height: 60,
               width: 60
            }, 500);	
            $(this).removeClass('active');
         } else {
            $(this).addClass('active');
            $(this).closest('.img-info').addClass('active')
            $(this).closest('.img-info').stop(true,false).removeAttr('style').addClass('img-info-animate', {duration:500});
            $(this).next('.img-info-content').animate({
               opacity :'1'
            }, 500);
            $(this).next('.img-info-content').addClass('active');
         }
      }); 
   };

   toggleExpand();

</script>

<?php 
require_once('mi-footer.php');
require_once('mi-final.php');
?>

</body>
</html>