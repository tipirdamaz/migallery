<nav class="navbar fixed-top navbar-expand-md navbar-light bg-white">
    <div class="container">
        <a href="javascript:" class="navbar-brand font-weight-bold fw-bold">MIGallery</a>
        <button type="button" class="navbar-toggler float-right float-end" data-toggle="collapse" data-bs-toggle="collapse" data-target="#navbarCollapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="gallery-man.php" class="nav-item nav-link <?php if (basename($_SERVER['PHP_SELF'])=='gallery-man.php') {?>active<?php }?>"><?php echo $lang->nav->gallery_man;?></a>
                <a href="gallery-list.php" class="nav-item nav-link <?php if (basename($_SERVER['PHP_SELF'])=='gallery-list.php') {?>active<?php }?>"><?php echo $lang->nav->gallery_list;?></a>
                <a href="mi-uploader.php" class="nav-item nav-link <?php if (basename($_SERVER['REQUEST_URI'])=='mi-uploader.php') {?>active<?php }?>"><?php echo $lang->nav->uploader;?></a>
            </div>
            <div class="navbar-nav ml-auto ms-auto">
                <a href="javascript:history.go(-1)" class="nav-item nav-link"><i class="fa fa-chevron-left"></i> <?php echo $lang->nav->back;?></a>
            </div>
        </div>
    </div>
</nav>

<div class="row w-100" style="height:50px"></div>