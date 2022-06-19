<?php

   /**
   * List of image galleries for all users
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn from mi-init.php
   * @var {Array} $config from CONFIG_FILE
   * 
   * $_GET vars
   * @var {String} $_GET['order'] sort galleries asc or desc
   * @var {Integer} $_GET['rpp'] result per page
   * @var {Integer} $_GET['page'] page number
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
require_once(GALLERY_DIR.'/Pagination.class.php');
Translator::select($config['lang']);
$langCode = Translator::getLangCode();
$langDir = Translator::getDirection();
$langNums = Translator::getLangNumbers();
$lang = Translator::translate();
$date_format = Translator::getLocaleDateFormat().' H:i';
?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $lang->gallery_list->title;?></title>
<meta name="keywords" content="MIGallery, List of galleries" />
<meta name="description" content="MIGallery, List of galleries" />
<meta name="dcterms.rights" content="MIGallery" />
<meta name="dcterms.audience" content="Global" />

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the css files according to the version you use. -->

<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs4.css" rel="stylesheet" type="text/css"/>
<!--
<link href="bootstrap-5.0.0-beta2/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs5.css" rel="stylesheet" type="text/css"/>
-->

<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>


<link href="<?php echo GALLERY_URL;?>/css/gallery-list.css" rel="stylesheet" type="text/css"/>
<?php if ($langDir == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/migallery-rtl.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/gallery-list-rtl.css" rel="stylesheet" type="text/css"/>
<?php if ($config['img_direction'] == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/gallery-list-img-rtl.css" rel="stylesheet" type="text/css"/>
<?php }?>
<?php }?>
</head>
<body>

<?php require_once('mi-navbar.php');?>

<?php 

   MIGallery::init($config, $db_conn);
   $gal_count = MIGallery::getGalleryCount();

   if (!isset($_GET['order'])) $_GET['order'] = 'asc';
   else if (!($_GET['order'] == 'asc' || $_GET['order'] == 'desc')) {
      $_GET['order'] == 'asc';
   }

   if (!isset($_GET['rpp'])) $_GET['rpp'] = 12; //result per page
   $result_per_page = $_GET['rpp'];

   if (!isset($_GET['page'])) $_GET['page'] = 1; //page number
   $page_num = $_GET['page'];

   $start_record_num = ($page_num-1)*$result_per_page;

   $total_page = ceil($gal_count / $result_per_page);

   $gals = MIGallery::getGalleryList($start_record_num, $result_per_page, $_GET['order']);

?>

<div class="container list-page"><!-- page container -->

  <div class="row d-flex justify-content-center mt-4 pt-3"><!-- page row -->

    <div class="col-12"><!-- page col -->

      <div class="row w-100 gallery-list-header border-bottom mx-0 mb-3"><!-- header row -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
          <div class="float-left float-start">
            <h5><?php echo $lang->gallery_list->header;?></h5>
          </div>
          <div class="float-right float-end">
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
          <select id="order" style="float:right;margin-left:10px">
            <option value="asc" <?php if($_GET['order']=='asc') echo 'selected';?>><?php echo $lang->gallery_list->sort->asc;?></option>
            <option value="desc" <?php if($_GET['order']=='desc') echo 'selected';?>><?php echo $lang->gallery_list->sort->desc;?></option>
          </select>
          <select id="rpp" style="float:right;margin-left:10px">
            <option value="12" <?php if($_GET['rpp']=='12') echo 'selected';?>><?php echo Translator::convertNumToLang(12);?></option>
            <option value="24" <?php if($_GET['rpp']=='24') echo 'selected';?>><?php echo Translator::convertNumToLang(24);?></option>
            <option value="36" <?php if($_GET['rpp']=='36') echo 'selected';?>><?php echo Translator::convertNumToLang(36);?></option>
          </select>
        </div>
      </div><!--/ header row -->

      <div class="row gallery-list-wrapper"><!-- gallery list row -->

<?php

      for($i=0; $i<count($gals); $i++) 
      {
         if ($gals[$i]['thumb'] != '') {
      	    $thumb_name = $gals[$i]['thumb'];
      	    if (file_exists(sprintf(IMG_THUMB_C_DIR, $gals[$i]['id']).'/'.$thumb_name)) {
      	       $tn_img = sprintf(IMG_THUMB_C_URL, $gals[$i]['id']).'/'.$thumb_name;
		    } else {
      	       $tn_img = GALLERY_URL.'/no-photo.png';
		    }
		 } else {
      	    $tn_img = GALLERY_URL.'/no-photo.png';
		 }
		 
		 $gal_name = htmlentities($gals[$i]['name'], ENT_QUOTES, 'UTF-8');
		 $gal_desc = htmlentities($gals[$i]['desc'], ENT_QUOTES, 'UTF-8');
		 
		 MIGallery::selectGallery($gals[$i]['id']);
		 $img_count = MIGallery::getImageCountFromGallery();
		
		 if ($_GET['order'] == 'asc') $gal_number = $start_record_num + $i +1;
		 else $gal_number = $gal_count - $start_record_num - $i;
?>
        <div id="col_gal_<?php echo $gals[$i]['id'];?>" class="thumb_gal col-12 col-md-6 col-lg-3 col-xl-3 col-xxl-2">
          <div class="card mb-3"><!-- card -->
            <div class="card-img-body">
              <!--<div class="gal-number" id="gal_number_<?php echo $gals[$i]['id'];?>"><?php echo Translator::convertNumToLang($gal_number);?></div>-->
              <a href="gallery-view.php?gal_id=<?php echo $gals[$i]['id'];?>" title="<?php echo $lang->gallery_list->card->view->title;?>">
                <img class="card-img" src="<?php echo $tn_img;?>" alt="<?php echo $gal_name;?>" style="cursor:pointer" title="<?php echo $lang->gallery_list->card->view->title;?>">
              </a>
              <a href="gallery-view.php?gal_id=<?php echo $gals[$i]['id'];?>" class="text-white text-nowrap p-1" title="<?php echo $lang->gallery_list->card->view->title;?>">
                <div class="row d-flex mx-auto w-100 btn btn-dark">
                  <div class="col-6 d-inline-flex justify-content-start p-0">
                    <span class="mr-2 me-2">
                      <?php echo Translator::convertNumToLang($img_count);?>
                    </span>
                    <span class="text-nowrap">
                      <?php echo $lang->gallery_list->card->images;?>
                    </span>
                  </div>
                  <div class="col-6 d-inline-flex justify-content-end p-0">
                    <i class="fa fa-eye fa-lg mt-1 mr-1 me-1"></i>
                    <span>
                      <?php echo $lang->gallery_list->card->view->button;?>
                    </span>
                  </div>
                </div>
              </a>
            </div>  
            <div class="card-body">
              <h4 class="card-title">
                <?php echo $gal_name;?>
              </h4>
              <p class="card-text">
                <?php echo $gal_desc;?>
              </p>
              <p class="card-date text-secondary">
                <span>
                  <?php echo $lang->gallery_list->card->date;?>
                </span>
                <span>
                  <?php echo date($date_format, intval($gals[$i]['time']));?>
                </span>
              </p>
            </div>
          </div><!--/ card -->
        </div>
<?php
      }

      if ($gal_count == 0) {
?>
        <div class="col-12"><!-- gallery not found -->
          <div class="row w-100 alert alert-info gallery-list-alert ml-auto ms-auto mr-auto me-auto p-2" role="alert" style="font-size:.85rem;">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-2 px-0">
              <div class="float-left float-start">
                <?php echo $lang->gallery_list->alert;?>
              </div>
              <div class="float-left float-start">
              </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
              <div class="float-right float-end">
              </div>
              <div class="float-right float-end">
              </div>
            </div>
          </div>
        </div><!--/ gallery not found -->
<?php
      }

      $result = MIGallery::getResult();
      MIGallery::end();

      if($result['code'] == MIGallery::ERROR) {
         echo '<div class="col-12"><div class="alert alert-danger" role="alert">'.$result['result'].'</div></div>';
      }
?>

      </div><!--/ gallery list row -->

      <div class="row my-3"><!-- pagination row -->

        <div class="col-12"><!-- pagination col -->

<?php

$numberOfRecords = $gal_count;
$pageURL = $_SERVER['PHP_SELF'];
$currentPage = $page_num;
$resultsPerPage = $result_per_page;
$maximumLinksToDisplay = 10;
$displayArrows = true;
$additionalQuery = array(
    'rpp' => $_GET['rpp'],
    'order' => $_GET['order']
);

$pagerClass = 'pagination justify-content-center';
$liClass = 'page-item';
$liActiveClass = 'active';
$aClass = 'page-link';
$aActiveClass = '';

$linkLabels = Array(
    'first' => '&laquo;',
    'prev' => '&lt;',
    'next' => '&gt;',
    'last' => '&raquo;'
);

$titleLabels = Array(
    'page' => $lang->pagination->page,
    'first' => $lang->pagination->first,
    'prev' => $lang->pagination->prev,
    'next' => $lang->pagination->next,
    'last' => $lang->pagination->last
);

if ($langDir == 'rtl' && $config['img_direction'] == 'rtl') $paginationDir = 'rtl';
else $paginationDir = 'ltr';

Pagination::setLang($paginationDir, $langNums, $linkLabels, $titleLabels);
Pagination::setClasses($pagerClass, $liClass, $liActiveClass, $aClass, $aActiveClass);
echo Pagination::paging($numberOfRecords, $pageURL, $currentPage, $resultsPerPage, $maximumLinksToDisplay, $displayArrows, $additionalQuery);
?>

        </div><!--/ pagination col -->

      </div><!--/ pagination row -->

    </div><!--/ page col -->

  </div><!--/ page row -->

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

<script src="<?php echo GALLERY_URL;?>/lang/js/<?php echo $langCode;?>.js"></script>

<script>

$(function(){
  $('.container.list-page').css({ minHeight: $(window).innerHeight() - 150 });
  $(window).resize(function(){
    $('.container.list-page').css({ minHeight: $(window).innerHeight() -150 });
  });
});

$('#order').change(function(){
   var order = $(this).val();
   var rpp = $('#rpp').val();
   window.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $_GET['page'];?>&order="+order+"&rpp="+rpp;
});

$('#rpp').change(function(){
   var rpp = $(this).val();
   var order = $('#order').val();
   window.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $_GET['page'];?>&order="+order+"&rpp="+rpp;
});

</script>

<?php 
require_once('mi-footer.php');
require_once('mi-final.php');
?>

</body>
</html>