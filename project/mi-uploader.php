<?php 

   /**
   * mi-uploader.php 					: Save new gallery and manage images in gallery
   * mi-uploader.php?gal_id={Integer}	: Edit gallery and manage images in gallery
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn from mi-init.php
   * @var {Array} $config from CONFIG_FILE
   * @var {String} $sessid from mi-session.php
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

if (isset($_GET['gal_id']) && $_GET['gal_id']!='') {
   if (!is_numeric($_GET['gal_id'])) exit;
   $gal_id = $_GET['gal_id'];
   $page_title = $lang->uploader->edit->title;
   $page_header = $lang->uploader->edit->header;
} else {
   $gal_id = '';
   $page_title = $lang->uploader->new->title;
   $page_header = $lang->uploader->new->header;
}
?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $page_title;?></title>

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the css files according to the version you use. -->

<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs4.css" rel="stylesheet" type="text/css"/>
<!--
<link href="bootstrap-5.0.0-beta2/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs5.css" rel="stylesheet" type="text/css"/>
-->

<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/form.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/uploader.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<?php if ($langDir == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/migallery-rtl.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/uploader-rtl.css" rel="stylesheet" type="text/css"/>
<?php if ($config['img_direction'] == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/uploader-img-rtl.css" rel="stylesheet" type="text/css"/>
<?php }?>
<?php }?>
</head>
<body>

<?php 

unset($_SESSION['imgs_info']);
$_SESSION['imgs_info'] = array();


if ($gal_id != '')
{
   MIGallery::init($config, $db_conn, $gal_id);
   $gallery = MIGallery::initUploader($_SESSION['imgs_info']);
}

?>

<?php require_once('mi-navbar.php');?>

<div class="container mt-4 py-3"><!-- container -->

      <div class="row page-header-wrapper mx-auto mb-3">
        <h5 class="p-0"><?php echo $page_header;?></h5>
      </div>

      <div class="row mb-3"><!-- gallery form row -->
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

          <form id="gallery_form" class="form-inline bg-white mx-auto border" novalidate>

            <div class="row bg-light w-100 ml-auto ms-auto mr-auto me-auto py-3">
              <div class="col-12 font-weight-bold fw-bold">
                <?php echo $lang->uploader->gallery_info->title;?>
              </div>
            </div>

            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-3 mb-3">
              <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                <?php echo $lang->uploader->gallery_info->name->label;?>
              </div>
              <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
                <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="gal_name">
                  <?php echo $lang->uploader->gallery_info->name->label;?>
                </label>
                <div class="form_field">
                  <textarea id=gal_name name=gal_name class="form-control w-100 m-0" style="height:50px" rows="3" placeholder="<?php echo $lang->uploader->gallery_info->name->placeholder;?>" required><?php echo $gallery['name'];?></textarea>
                  <div class="invalid-feedback m-0" id="invalid_gal_name">
                    <i class="fa fa-warning"></i> <?php echo $lang->uploader->gallery_info->name->validate;?>
                  </div>
                </div>
                <div class="label-required float-left float-start">&bull;</div>
              </div>
              <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-secondary">
                <span id=gal_nameCount><?php echo mb_strlen($gallery['name'],'UTF-8');?></span>/55
              </div>
            </div>

            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-3 mb-3">
              <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                <?php echo $lang->uploader->gallery_info->description->label;?>
              </div>
              <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
                <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="gal_description">
                  <?php echo $lang->uploader->gallery_info->description->label;?>
                </label>
                <div class="form_field">
                  <textarea id=gal_description name=gal_description class="form-control w-100 m-0" style="height:100px" rows="3" placeholder="<?php echo $lang->uploader->gallery_info->description->placeholder;?>" required><?php echo $gallery['description'];?></textarea>
                  <div class="invalid-feedback m-0" id="invalid_gal_description">
                    <i class="fa fa-warning"></i> <?php echo $lang->uploader->gallery_info->description->validate;?>
                  </div>
                </div>
                <div class="label-required float-left float-start">&bull;</div>
              </div>
              <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-secondary">
                <span id=gal_descriptionCount><?php echo mb_strlen($gallery['description'], 'UTF-8');?></span>/155
              </div>
            </div>

            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-3 mb-3">
              <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                <?php echo $lang->uploader->gallery_info->keywords->label;?>
              </div>
              <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
                <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="gal_keywords">
                  <?php echo $lang->uploader->gallery_info->keywords->label;?>
                </label>
                <div class="form_field">
                  <textarea id=gal_keywords name=gal_keywords class="form-control w-100 m-0" style="height:100px" rows="3" placeholder="<?php echo $lang->uploader->gallery_info->keywords->placeholder;?>" required><?php echo $gallery['keywords'];?></textarea>
                  <div class="invalid-feedback m-0" id="invalid_gal_keywords">
                    <i class="fa fa-warning"></i> <?php echo $lang->uploader->gallery_info->keywords->validate;?>
                  </div>
                </div>
                <div class="label-required float-left float-start">&bull;</div>
              </div>
              <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 text-secondary">
                <span id=gal_keywordsCount><?php echo mb_strlen($gallery['keywords'], 'UTF-8');?></span>/155
              </div>
            </div>

          </form>

        </div>
      </div><!--/ gallery form row -->

      <div class="row my-3"><!-- uploader row -->

          <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="uploader"><!-- image uploader -->

            <!-- Uploader Guide -->
            <div class="uploader-guide btn-light row w-100 ml-auto ms-auto mr-auto me-auto mt-3 mb-3 border py-3 collapsed" data-toggle="collapse" data-bs-toggle="collapse" data-target="#uploader-guide-collapse" data-bs-target="#uploader-guide-collapse" aria-expanded="false" aria-controls="uploader-guide-collapse">
              <div class="col-10">
                <span class="d-block font-weight-bold fw-bold"><?php echo $lang->uploader->uploader->info->title;?></span>
              </div>
              <div class="col-2" style="text-align:right">
                <span class="d-block fa fa-chevron-down mt-1"></span>
              </div>
            </div>

            <div class="collapse row ml-auto ms-auto mr-auto me-auto" id="uploader-guide-collapse">

              <div class="col-12 b-upload__hint alert alert-info" role="alert">
                <?php printf($lang->uploader->uploader->info->body, $config['up_img_min_width'], $config['up_img_min_height'], $config['up_img_max_width'], $config['up_img_max_height'], $config['max_file_size'], $config['max_upload_limit']);?>
              </div>

              <div class="col-12 b-upload__hint alert alert-info" role="alert">
                <span id="move_msg_desktop" class="d-none"><?php echo $lang->uploader->uploader->thumb->move;?></span>
                <span id="move_msg_mobile" class="d-none"><?php echo $lang->uploader->uploader->thumb->move_mobile;?></span>
              </div>

            </div>

            <!--/ Uploader Guide -->

            <?php if (count($_SESSION['imgs_info'])>0) {?>

            <!-- Select all checkbox, button -->
            <div class="row">
              <div class="col-12 uploader-select-all-wrapper">
                <div class="d-block m-2" style="float:left">
                  <input type="checkbox" id="select_all" value="0" class="selectAll filled-in" style="width:18px;height:18px">
                  <label class="d-inline-block" for="select_all" style="vertical-align: middle;margin-bottom: 0"><?php echo $lang->uploader->uploader->select_all;?></label>
                </div>
                <div class="d-block mb-2 ml-1 ms-1" style="float:left">
                  <input type="button" class="btn-fileapi btn btn-default multi__del" onclick="Uploader.imageDelExistingMulti()" value="<?php echo $lang->uploader->uploader->select_del;?>" style="font-size:13px">
                </div>
              </div>
            </div>
            <!--/ Select all checkbox, button -->

            <?php }?>


            <?php require_once(GALLERY_DIR.'/uploader-thumb-existing.php');?>
            <?php require_once(GALLERY_DIR.'/uploader-thumb-template.php');?>


            <!-- uploader alerts -->
      
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto d-flex justify-content-center">
              <div class="col-12 px-0">
                <div id="upload_alert" style="display:none;width:100%" class="b-upload__hint alert alert-info mb-0" role="alert">
                  <div id="upload_ing" style="display:none;width:100%">
                    <i class="icon-loading mr-2 me-2"></i>
                    <?php echo $lang->uploader->uploader->uploading;?>
                  </div>
                  <div id="upload_done" style="display:none;width:100%">
                    <i class="fa fa-check text-success mr-2 me-2"></i>
                    <?php echo $lang->uploader->uploader->uploaded;?>
                  </div>
                  <div id="upload_error" class="alert-danger" style="display:none;width:100%">
                    <i class="fa fa-times-circle-o text-danger mr-2 me-2"></i>
                    <?php echo $lang->uploader->uploader->error;?>
                  </div>
                </div>
              </div>
            </div>

            <!--/ uploader alerts -->

            <!-- uploader buttons -->

            <div class="row uploader-btns-wrapper">
              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 uploader-btns-wrapper">

                <div class="btn-fileapi btn btn-primary js-fileapi-wrapper mt-3">
                  <span><?php echo $lang->uploader->uploader->browse;?></span>
                  <input type="file" name="filedata" multiple/>
                </div>

                <div class="js-upload btn-fileapi btn btn-primary mt-3">
                  <span><?php echo $lang->uploader->uploader->upload;?></span>
                </div>

              </div>
              <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-3">
<?php if (1/*$gal_id != ''*/) {?>
                <div id="btn-save-changes" class="btn-fileapi btn btn-secondary disabled" onclick="Uploader.saveImageInfoChanges()">
                  <?php echo $lang->uploader->uploader->save_changes;?>
                </div>
<?php }?>
              </div>
            </div>

            <!--/ uploader buttons -->

            <hr>

          </div><!--/ image uploader -->

      </div><!--/ uploader row -->

      <!-- gallery alert -->
      
      <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 d-flex justify-content-center">
        <div class="col-12 px-0">
          <div id="gallery_save_info" style="display:none;width:100%" class="alert alert-info" role="alert">
          </div>
        </div>
      </div>

      <!--/ gallery alert -->

      <!-- gallery save / update button -->
      
      <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 d-flex justify-content-center">
        <div class="col-8 col-md-6 col-lg-4 col-xl-3 mx-0 px-0">
          <span id="gallery_submit_ttip" class="d-inline-block w-100" data-toggle="tooltip" data-bs-toggle="tooltip" data-placement="top" data-bs-placement="top" data-html="true" data-bs-html="true" title="">
            <button type="button" id="gallery_submit" class="btn btn-primary w-100 float-right float-end text-nowrap">
              <?php echo $gal_id != '' ? $lang->uploader->gallery_info->submit->update : $lang->uploader->gallery_info->submit->save; ?>
            </button>
          </span>
        </div>
      </div>

      <!--/ gallery save / update button -->

      <!-- image crop modal -->

      <div id="crop-popup" class="crop_popup" style="display: none;">
        <div class="crop_popup__body"><div class="js-img"></div></div>
        <div style="margin: 0 10px 5px 10px; text-align:right;display:block;float:right">
          <div class="js-cancel btn btn-secondary">
            <?php echo $lang->uploader->modal->crop->cancel;?>
          </div>
        </div>
        <div style="margin: 0 0 5px 0; text-align:right;display:block;float:right">
          <div class="js-crop btn btn-primary">
            <?php echo $lang->uploader->modal->crop->crop;?>
          </div>
        </div>
      </div>

      <!--/ image crop modal -->

      <!-- delete uploaded image modal -->

      <div class="modal fade" id="imageDelUploadedModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelImageDelUp" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabelImageDelUp">
                    <?php echo $lang->uploader->modal->del_up_img->label;?>
                  </h5>
                  <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->uploader->modal->del_up_img->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-body">
              <form class="form-inline">
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                  <div class="col-12">
                    <span class="text-danger">
                      <?php echo $lang->uploader->modal->del_up_img->text;?>
                    </span>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                <div class="col-6">
                  <button id="btn-img-del-uploaded" type="button" class="btn btn-danger w-75"><?php echo $lang->uploader->modal->del_up_img->command;?></button>
                </div>
                <div class="col-6 text-right text-end">
                  <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75"><?php echo $lang->uploader->modal->del_up_img->cancel;?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--/ delete uploaded image modal -->

      <!-- delete image modal -->

      <div class="modal fade" id="imageDelModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelImageDel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabelImageDel">
                    <?php echo $lang->uploader->modal->del_img->label;?>
                  </h5>
                  <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->uploader->modal->del_img->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-body">
              <form class="form-inline">
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                  <div class="col-12">
                    <span class="text-danger">
                      <?php echo $lang->uploader->modal->del_img->text;?>
                    </span>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                <div class="col-6">
                  <button id="btn-del-image" type="button" class="btn btn-danger w-75">
                    <?php echo $lang->uploader->modal->del_img->command;?>
                  </button>
                </div>
                <div class="col-6 text-right text-end">
                  <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75">
                    <?php echo $lang->uploader->modal->del_img->cancel;?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--/ delete image modal -->

      <!-- image multi delete modal -->

      <div class="modal fade" id="imageMultiDelModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelImageDelMulti" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabelImageDelMulti">
                    <?php echo $lang->uploader->modal->del_img_multi->label;?>
                  </h5>
                  <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->uploader->modal->del_img_multi->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-body">
              <form class="form-inline">
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                  <div class="col-12">
                    <span class="text-danger">
                      <?php echo $lang->uploader->modal->del_img_multi->text;?>
                    </span>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                <div class="col-6">
                  <button id="btn-del-image-multi" type="button" class="btn btn-danger w-75" onclick="Uploader.delMultiImageFromExistingGallery()">
                    <?php echo $lang->uploader->modal->del_img_multi->command;?>
                  </button>
                </div>
                <div class="col-6 text-right text-end">
                  <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75">
                    <?php echo $lang->uploader->modal->del_img_multi->cancel;?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--/ image multi delete modal -->

      <!-- image info modal -->

      <div class="modal fade" id="imageInfoModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelImageInfo" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabelImageInfo">
                    <?php echo $lang->uploader->modal->img_info->label;?>
                  </h5>
                  <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->uploader->modal->img_info->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-body">
              <form class="form-inline">
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-0 mb-3">
                  <div class="col-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->filename;?>
                  </div>
                  <div class="col-7 col-md-8 pl-0">
                    <div class="form_field" id="image_info_file"></div>
                  </div>
                </div>
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-2 mb-3">
                  <div class="col-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->filesize;?>
                  </div>
                  <div class="col-7 col-md-8 pl-0">
                    <div class="form_field" id="image_info_size"></div>
                  </div>
                </div>
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-2 mb-3">
                  <div class="col-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->date;?>
                  </div>
                  <div class="col-7 col-md-8 pl-0">
                    <div class="form_field" id="image_info_date"></div>
                  </div>
                </div>
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-2 mb-3">
                  <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->title->label;?>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pl-0">
                    <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="image_info_title">
                      <?php echo $lang->uploader->modal->img_info->title->label;?>
                    </label>
                    <div class="form_field">
                      <textarea id=image_info_title name=image_info_title class="form-control w-100 m-0" style="height:50px" rows="3" placeholder="<?php echo $lang->uploader->modal->img_info->title->placeholder;?>"></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 pl-0 text-secondary">
                    <span id=image_info_titleCount></span>/55
                  </div>
                </div>
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-2 mb-3">
                  <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->desc->label;?>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pl-0">
                    <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="image_info_desc">
                      <?php echo $lang->uploader->modal->img_info->desc->label;?>
                    </label>
                    <div class="form_field">
                      <textarea id=image_info_desc name=image_info_desc class="form-control w-100 m-0" style="height:90px" rows="3" placeholder="<?php echo $lang->uploader->modal->img_info->desc->placeholder;?>"></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 pl-0 text-secondary">
                    <span id=image_info_descCount></span>/155
                  </div>
                </div>
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mt-2 mb-3">
                  <div class="d-none d-sm-block col-md-3 col-lg-3 col-xl-3 form_label">
                    <?php echo $lang->uploader->modal->img_info->keyw->label;?>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pl-0">
                    <label class="mr-sm-2 ml-2 ms-2 d-sm-none" for="image_info_keyw">
                      <?php echo $lang->uploader->modal->img_info->keyw->label;?>
                    </label>
                    <div class="form_field">
                      <textarea id=image_info_keyw name=image_info_keyw class="form-control w-100 m-0" style="height:90px" rows="3" placeholder="<?php echo $lang->uploader->modal->img_info->keyw->placeholder;?>"></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2 pl-0 text-secondary">
                    <span id=image_info_keywCount></span>/155
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                <div class="col-6">
                  <button id="btn-save-image-info" type="button" class="btn btn-primary w-100 text-nowrap" onclick="Uploader.imageInfoSave()">
                    <?php echo $lang->uploader->modal->img_info->command;?>
                  </button>
                </div>
                <div class="col-6 text-right text-end">
                  <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75">
                    <?php echo $lang->uploader->modal->img_info->cancel;?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--/ image info modal -->

      <!-- Process Result Modal -->

      <div class="modal fade" id="processResultModal" tabindex="-1" role="dialog" aria-labelledby="processResultLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="modal-header">
                  <h5 class="modal-title text-danger" id="processResultLabel">
                    <?php echo $lang->uploader->modal->result->label;?>
                  </h5>
                  <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->uploader->modal->result->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
                  </button>
                </div>
              </div>
            </div>
            <div class="modal-body">
              <form class="form-inline">
                <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                  <div class="col-12">
                    <div class="text-danger" id="processResultMsg"></div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
                <div class="col-12 text-center">
                  <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-50">
                    <?php echo $lang->uploader->modal->result->command;?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--/ Process Result Modal -->

</div><!--/ container -->

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery-ui-1.12.1.custom.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jquery.mobile-events.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jquery.ui.touch-punch.js"></script>

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the js files according to the version you use. -->

<script src="bootstrap-4.5.3/popper.min.js"></script>
<script src="bootstrap-4.5.3/bootstrap.min.js"></script>
<!--
<script src="bootstrap-5.0.0-beta2/popper.min.js"></script>
<script src="bootstrap-5.0.0-beta2/bootstrap.min.js"></script>
-->

<script src="<?php echo GALLERY_URL;?>/plugins/jquery.mask.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/lang/js/<?php echo $langCode;?>.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/util.js"></script>
<script>
window.FileAPI = {
  debug: <?php echo $config['debug'] ? 'true' : 'false';?>,
  staticPath: '/js/FileAPI/lib/',
  galleryUrl: "<?php echo GALLERY_URL;?>",
  imgBaseUrl: "<?php echo IMG_BASE_URL;?>",
  sessid: "<?php echo $sessid;?>",
  gal_id: "<?php echo $gal_id;?>",
  maxFiles: <?php echo $config['max_upload_limit'] - count($_SESSION['imgs_info']);?>,
  maxSize: <?php echo $config['max_file_size'];?>,
  minWidth: <?php echo $config['up_img_min_width'];?>,
  minHeight: <?php echo $config['up_img_min_height'];?>,
  maxWidth: <?php echo $config['up_img_max_width'];?>,
  maxHeight: <?php echo $config['up_img_max_height'];?>,
  resizeMaxWidth: <?php echo $config['img_full_width'];?>,
  resizeMaxHeight: <?php echo $config['img_full_height'];?>,
  jpgQuality: <?php echo round($config['jpg_quality']/100, 2);?>,
  wmUse: <?php echo $config['wm_use'];?>,
  wmPosition: <?php echo $config['wm_position'];?>,
  uploaderId: 'uploader'
};
</script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/canvas-to-blob.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/FileAPI.core.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI.core.patch.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/FileAPI.Image.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/load-image-ios.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/FileAPI.Form.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/lib/FileAPI.XHR.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/FileAPI/plugins/FileAPI.exif.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/jquery.fileapi.patch.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/jquery.fileapi.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jquery.modal2.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jcrop/js/jquery.Jcrop.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/uploader.js"></script>
<script>
$(function () {

  $('[data-toggle="tooltip"]').tooltip();

  /* uploader guide collapse */

  $('.uploader-guide').click(function(){
    var icon = $(this).find("span.fa");
    if (icon.hasClass("fa-chevron-down")) {
      icon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
    } else {
      icon.removeClass("fa-chevron-up").addClass("fa-chevron-down");
    }
  });
});


(function() {
  'use strict';

   var form_is_invalid = false;
   var validate_elem = ["gal_name", "gal_description", "gal_keywords"];
  
   window.addEventListener('load', function() {

      /* form validation */
      
      var form = document.getElementById('gallery_form');
      var btn = document.getElementById('gallery_submit');

      for (var i=0; i<validate_elem.length; i++) {
         document.getElementById(validate_elem[i]).required = false;
      }

      for (var i=0; i<validate_elem.length; i++) {
         document.getElementById(validate_elem[i]).addEventListener('change', function(event) {

            form_is_invalid = false;
            var id = this.getAttribute('id');

            if(this.value == '') {
               document.getElementById(id).required = true;
               document.getElementById("invalid_"+id).classList.remove('d-none');
               document.getElementById("invalid_"+id).classList.add('d-block');
               form_is_invalid = true;
            } else {
               document.getElementById(id).required = false;
               document.getElementById("invalid_"+id).classList.remove('d-block');
               document.getElementById("invalid_"+id).classList.add('d-none');
            }
         }, false);
      }

      btn.addEventListener('click', function(event) {
    	
         form_is_invalid = false;

         for (var i=0; i<validate_elem.length; i++) {
            document.getElementById(validate_elem[i]).required = false;
         }

         for (var i=0; i<validate_elem.length; i++) {
            var id = validate_elem[i];
            if(document.getElementById(id).value == '') {
               document.getElementById(id).required = true;
               document.getElementById("invalid_"+id).classList.remove('d-none');
               document.getElementById("invalid_"+id).classList.add('d-block');
               form_is_invalid = true;
            } else {
               document.getElementById(id).required = false;
               document.getElementById("invalid_"+id).classList.remove('d-block');
               document.getElementById("invalid_"+id).classList.add('d-none');
            }
         }

         for (var i=0; i<validate_elem.length; i++) {
            if($('#'+validate_elem[i]).val() == '') {
               var pos = getOffset(document.getElementById(validate_elem[i])).top -120;
               $('html, body').animate({scrollTop: pos}, "slow");
               break;
            }
         }

         if (form_is_invalid) {
            return false;
         } else {
            if (FileAPI.gal_id != '') {
               $('#gallery_save_info').removeClass('d-none alert-danger alert-success').addClass('d-block alert-info').html("<span class=\"icon-loading\"></span> " + translateJS.uploader.galleryUpdateAlert);

               Uploader.galleryUpdate();
            } else {
               $('#gallery_save_info').removeClass('d-none alert-danger alert-success').addClass('d-block alert-info').html("<span class=\"icon-loading\"></span> " + translateJS.uploader.galleryInsertAlert);

               Uploader.galleryInsert();
            }
         }
      }, false);

      $('#gal_name').charCount({counter:'gal_nameCount', maxlen:55});
      $('#gal_name').autogrow();
      $('#gal_description').charCount({counter:'gal_descriptionCount', maxlen:155});
      $('#gal_description').autogrow();
      $('#gal_keywords').charCount({counter:'gal_keywordsCount', maxlen:155});
      $('#gal_keywords').autogrow();
      $('#image_info_title').charCount({counter:'image_info_titleCount', maxlen:55});
      $('#image_info_title').autogrow();
      $('#image_info_desc').charCount({counter:'image_info_descCount', maxlen:155});
      $('#image_info_desc').autogrow();
      $('#image_info_keyw').charCount({counter:'image_info_keywCount', maxlen:155});
      $('#image_info_keyw').autogrow();

      $('#gal_name').on('keyup keypress blur change', function(e) {
         $('#gallery_submit').prop("disabled", false);
      });
      $('#gal_description').on('keyup keypress blur change', function(e) {
         $('#gallery_submit').prop("disabled", false);
      });
      $('#gal_keywords').on('keyup keypress blur change', function(e) {
         $('#gallery_submit').prop("disabled", false);
      });

      
      /* select thumb */
      
      let chkImgElems = document.getElementsByClassName("chkImg");
      for (let i = 0; i < chkImgElems.length; i++) {
         chkImgElems[i].addEventListener('change', function () {
            if (this.checked) {
	   	       this.closest(".card-body").classList.remove("thumb_unselected");
	   	       this.closest(".card-body").classList.add("thumb_selected");
	        } else {
	   	       this.closest(".card-body").classList.remove("thumb_selected");
	   	       this.closest(".card-body").classList.add("thumb_unselected");
	        }
         }, false);
      }

      /* select all thumb */

      let elem_selall = document.getElementById('select_all');
      if (elem_selall) {
         elem_selall.addEventListener('click', function (e) {
         let chkImgElems = document.getElementsByClassName("chkImg");
            for (let i = 0; i < chkImgElems.length; i++) {
               chkImgElems[i].checked = this.checked;
               if (chkImgElems[i].checked) {
	   	          chkImgElems[i].closest(".card-body").classList.remove("thumb_unselected");
	   	          chkImgElems[i].closest(".card-body").classList.add("thumb_selected");
	           } else {
	   	          chkImgElems[i].closest(".card-body").classList.remove("thumb_selected");
	   	          chkImgElems[i].closest(".card-body").classList.add("thumb_unselected");
	           }
	        }
         }, false);
      }  

   }, false); // window load event listener

})();

</script>


<script type="text/javascript">

$isPhoneOrTablet = isPhoneOrTablet();

if ($isPhoneOrTablet) {
   $('#move_msg_mobile').removeClass('d-none');
} else {
   $('#move_msg_desktop').removeClass('d-none');
}

window.addEventListener('load', function() {

   /**
   * During gallery editing, existing images are moved to the sorting field where new images will be added.
   */
   
   $('#existingFiles > div').each(function () {
      $('#sortFile').append($(this));
   });
   $('#existingFiles').remove();
   $('#sortFile').removeClass('d-none');

   if (FileAPI.gal_id != ''){
      Uploader.fileInfoList.addExistingFiles('thumb_exist');
   }

}, false);


$("#sortFile").sortable({
   //grid: [ 72, 96 ],
   tolerance: "pointer",
   //revert: true,
   //scroll: false,
   start: function( event, ui ) {
      Uploader.fileInfoList.sortingStartIndex(ui.item.index());
   },
   stop: function( event, ui ) {
      Uploader.fileInfoList.sortingStopIndex(ui.item.index());
      Uploader.fileInfoList.reOrderFiles();
      Uploader.fileInfoList.reNumberThumbs();
      var sortedFileNames = Uploader.fileInfoList.getFileNames('new');
      $('#uploader').fileapi('sortUploadQueue', sortedFileNames);
      if (FileAPI.gal_id != '' && FileAPI.readyForUpload.getFileCount()==0) {
         $('#btn-save-changes').css('cursor', 'pointer');
         $('#btn-save-changes').removeClass('btn-secondary disabled').addClass('btn-warning');
         Uploader.submit_button_status('change', true);
      }
      $("#sortFile").sortable("disable");
      $sortableEnabled = false;
      $tapped = null;
      $('.thumb').each(function() {
        $(this).find('.card').removeClass('active');
   	    $(this).find('.card > .card-img-body > .move_thumb').removeClass('active');
      });
   }
}).sortable("disable");


var $tapped = null;
var $sortableEnabled = false;

window.add_thumb_events = function () {
  $('.thumb').on('taphold tap tapstart tapend tapmove mouseover mouseout', function(e) { 

    if ($isPhoneOrTablet && e.type == 'mouseover') return false;
    
    var active = $(this);
	var active_class = 'hover';

    if (e.type == 'taphold') {
	   active_class = 'tapped';
       $tapped = $(this);
	}

    if (($isPhoneOrTablet && $(this).is($tapped)) || (!$isPhoneOrTablet && e.type == 'mouseover')) {
	   if (!$sortableEnabled) {
	      $("#sortFile").sortable("enable");
	      $sortableEnabled = true;
          $('.thumb').each(function() {
             if ($(this).is(active)) {
                $(this).find('.card').addClass('active ' + active_class);
       	        $(this).find('.card > .card-img-body > .move_thumb').addClass('active ' + active_class);
             } else {
       	        $(this).find('.card').removeClass('active ' + active_class);
       	        $(this).find('.card > .card-img-body > .move_thumb').removeClass('active ' + active_class);
             }
          });
	   }
    } else if ($isPhoneOrTablet || (!$isPhoneOrTablet && e.type == 'mouseout')) {
       if ($sortableEnabled) {
         $("#sortFile").sortable("disable");
         $sortableEnabled = false;
         $tapped = null;
         $('.thumb').each(function() {
            $(this).find('.card').removeClass('active');
            $(this).find('.card > .card-img-body > .move_thumb').removeClass('active');
         });
       }
	}
  });
};

add_thumb_events();

</script>

<?php 
require_once('mi-footer.php');
require_once('mi-final.php');
?>

</body>
</html>
