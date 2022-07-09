<?php 
include($_SERVER["DOCUMENT_ROOT"]."/company/"."\controllers\Company.php");
$database=new MyPDO();
$database->create_company_tables();
$database->creat_categories();
$database->adding_cities_areas_tables();
$results=$database->get_all_comanies();
$full_num_comany=$database->count_of_companies()["num_of_companies"];
if(!isset($_SESSION["auth"]))
{
    header("location:/company/views\user\login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>companies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        .svg-inline--fa {
            height: 1em;
        }

        .alert.align-items-center {
            width: fit-content;
            background-color: hsl(354deg 72% 86% / 25%);
            position: absolute;
            top: 0;
            left: 40%;
        }

        .comapny-images {
            width: 100px;
            height: 70px;
            object-fit: cover;
            object-position: top;
            cursor: pointer;
        }

        .slide-images-company {
            width: 500px !important;
            height: 500px;
            object-fit: contain;
        }

        .next-span-container,
        .prev-span-container {
            box-shadow: 0px 0px 20px 9px #888888;
            width: fit-content;
            height: fit-content;
            background: rgb(2 2 2 / 40%);
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }

        .image-slide-copany .modal-dialog.modal-dialog-centered {
            max-width: 600px !important;
            width: 550px;
        }

        .image-slide-copany .modal-dialog.modal-dialog-centered .carousel-inner.rounded {
            width: 550px;
            height: 500px;
        }

        .page-item {
            margin-left: 5px;
            /* margin-left:5px; */
        }

        .delete-action-form {
            margin-top: 10px;
        }

        .rounded-circle {
            width: 100px;
            height: 100px;
            border-radius: 5px !important;
        }

        .images-preview {
            overflow-y: auto;
            max-height: 200px;
            overflow-x: hidden;
            height: auto;
        }

        .images-preview img {
            margin-bottom: 10px;
            object-fit: cover;
            object-position: center;
        }

        .offcanvas-edit-forms {
            width: 40%;
        }

        .delete-image-company-floating {
            position: relative;
            color: #ffffff;
            transform: translate(42px, -68px);
            cursor: pointer;
            width: fit-content;
            display: none;
            padding: 5px 5px 4px;
            background-color: #d10a34;
            border-radius: 4px;
            align-items: center;
            justify-content: center;
            align-content: center;
            width: 28px;
            height: 28px;
        }

        .delete-image-company-floating:hover {
            display: flex;
        }

        .image-preview-actions .rounded-circle:hover~.delete-image-company-floating {
            display: flex;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding-right: 2.5rem;
            padding-left: 2.5rem;
        }

        .list-class-company {
            text-align: center;
            margin-bottom: 20px;
        }

        th[scope="col"],
        th[scope="row"] {
            background: darkgray;
        }

        td {
            box-shadow: unset !important;
        }

        body {
            background: rgb(220, 220, 220);
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: lightcyan;
            vertical-align: middle !important;
            background: hsl(293deg 100% 39% / 2%);
            max-width: 300px;
            text-align: center;
        }

        .table {
            margin: 20px 0 !important
        }

        .edit-btn {
            width: 100%;
        }

        .delete-btn {
            width: 100%;
        }

        .text-700 {
            color: #525b75 !important;
        }

        .bg-200 {
            background-color: #e3e6ed !important;
        }

        .offcanvas-header,
        .modal-header {
            background: darkgray;
        }

        .modal-body,
        .modal-footer,
        .offcanvas-body {
            background: rgb(220, 220, 220);
        }

        #offcanvasRight {
            height: fit-content;
        }

        .form-user-edit-details {
            background: rgb(220, 220, 220);
        }
    </style>
</head>

<body>
    <?php include("layouts/navbar.php"); ?>
    <div class="container">
        <?php
                 if(isset($_SESSION["succss"]))
                 {
                     if($_SESSION["succss"]["page"]=="company"){
                         echo '<div class="alert alert-success d-flex align-items-center" role="alert"><svg class="svg-inline--fa fa-check-circle fa-w-16 text-success fs-3 me-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg><!-- <span class="fas fa-check-circle text-success fs-3 me-3"></span> Font Awesome fontawesome.com -->
                                 <p class="mb-0 flex-1">'.$_SESSION["succss"]["message"].'</p>
                                 <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                             </div>';
                             unset($_SESSION["succss"]);
                     }
                 }else if(isset($_SESSION["error"]))
                 {
                     if($_SESSION["error"]["page"]=="company"){
                         echo '<div class="alert alert-danger d-flex align-items-center" role="alert"><svg class="svg-inline--fa fa-times-circle fa-w-16 text-danger fs-3 me-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path></svg><!-- <span class="fas fa-times-circle text-danger fs-3 me-3"></span> Font Awesome fontawesome.com -->
                         <p class="mb-0 flex-1">'.$_SESSION["error"]["message"].'</p>
                         <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                       </div>';
                       unset($_SESSION["error"]);
                     }
                 }
                //  var_dump($results);
        ?>
        <h1 class="list-class-company">List of campanies</h1>
        <?php
            if($_SESSION["auth"]["role"]=="administrator"){
                echo "<div class='mt-1 mb-1'>";
                echo('<a class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#CampanyAdding">add company</a>');
                echo('<a class="btn btn-primary float-end" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">add category</a>');
                echo "</div>";

            }
        ?>
        <table class="table table-bordered table-striped fs--1 mb-0">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Phone</th>
                    <th scope="col">City</th>
                    <th scope="col">Area</th>
                    <th scope="col">Images</th>
                    <th scope="col">Create_at</th>
                    <th scope="col">Updated_at</th>
                    <?php
                        if($_SESSION["auth"]["role"]=="administrator"){
                            echo('<th scope="col">ACRIONS</th>');
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $key=>$value){?>
                <tr>
                    <th scope="row">
                        <?php 
                            // row count of field
                            if(isset($_SESSION["page"])){
                                $validnumber=filter_var($_SESSION["page"]["page_number"], FILTER_SANITIZE_NUMBER_INT);
                                $base_number=($validnumber-1)*$database::items_per_page;
                                $current_index=$base_number+$key+1;
                                echo $current_index;
                            }  else{
                                echo $key+1;
                            }
                        
                        ?>
                    </th>
                    <td><?php echo $value["name"];?></td>
                    <td><?php echo "<span class='badge bg-200 text-700 ms-2 fs--1 px-3 mb-2'>".implode("</span><span class='badge bg-200 text-700 ms-2 fs--1 px-3 mb-2'>",explode("___",$value["category"]))."</span>"; ?>
                    </td>
                    <td><?php echo $value["phone"];?></td>
                    <td><?php echo $database->get_city_by_id($value["city"])["governorate_name_en"];?></td>
                    <td><?php echo $database->get_area_by_id($value["area"])["city_name_en"];?></td>
                    <!-- company-avatar.jpg -->
                    <!-- if($value["images"]!="" && $value["images"]!=NULL)  -->
                    <td><img class="comapny-images" src="<?php
                            if($value["images"]!="" && $value["images"]!=NULL){
                                echo "/company/"."public/images/company/".explode("___",$value["images"])[0];
                            }
                            else{
                                echo "/company/"."public/images/company/company-avatar.png";
                            }
                                ?>" title="company images" data-bs-toggle="modal"
                            data-bs-target="#images<?php echo $value["id"]; ?>" alt="">
                    </td>
                    <td><?php echo $value["created_at"];?></td>
                    <td><?php echo $value["updated_at"];?></td>
                    <?php
                        if($_SESSION["auth"]["role"]=="administrator")
                        {
                            echo('<td>
                                        <a class="btn btn-primary edit-btn" data-bs-toggle="offcanvas" data-bs-target="#edit_compay_offcans'.$value["id"].'" aria-controls="offcanvasScrolling">Edit</a>
                                        <form class="delete-action-form" action="'."/company/"."controllers/Company.php".'" method="POST">
                                            <input type="hidden" name="delete" value="'.$value["id"].'">
                                            <button type="submit" class="btn btn-danger delete-btn">delete</button>
                                        </form>
                                </td>
                                ');
                        }
                    ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php 
                if(isset($_SESSION["page"]))
                {
                        $validnumber=filter_var($_SESSION["page"]["page_number"], FILTER_SANITIZE_NUMBER_INT);
                } else
                {
                    $validnumber=1;
                }

                $pages_count=ceil($full_num_comany/10);
                for($i=1;$i<=$pages_count;$i++)
                {
                    $class=$validnumber == $i ? 'active': '';
                    echo '<li class="page-item '.$class.'">
                            <form action="'."/company/"."controllers/Company.php".'" method="GET">
                                <input type="hidden" name="pagination" value="'.$i.'">
                                <button class="page-link" type="submit">'.$i.'</button>
                            </form>
                        </li>';
                }

                
                ?>
            </ul>
        </nav>
    </div>
    <?php
            if($_SESSION["auth"]["role"]=="administrator"){
                $city_area=$database->get_all_cities();
                $cities=$city_area["city"];
                $areas=$city_area["area"];
                $categories=$database->get_all_categories();
        ?>
    <!-- adding company modal -->
    <div class="modal fade" id="CampanyAdding" tabindex="-1" aria-labelledby="CampanyAddingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CampanyAddingLabel">Add Company</h5>
                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times fs--1"></span>
                    </button>
                </div>
                <form action="<?php echo "/company/controllers/Company.php";?>" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="basic-form-name">Name</label>
                            <input class="form-control" id="basic-form-name" name="name" type="text" placeholder="Name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-form-Category">Category</label>
                            <select class="form-select" name="category[]" id="basic-form-Category"
                                aria-label="Default select example" multiple required>
                                <option selected="selected">none</option>
                                <?php 
                                    foreach ($categories as $kcategories => $valcategories) {
                                        echo '<option value='.$valcategories["name"].'>'.$valcategories["name"]."</option>";
                                    }
                                ?>
                            </select>
                            <?php if(count($categories)==0){
                                    echo '<span class="badge bg-danger">Add some categories </span>';
                                    } 
                            ?>
                            <p class="badge bg-primary">to select more than one category press <span
                                    class="badge bg-secondary">ctrl</span></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-form-phone">Phone Number</label>
                            <input class="form-control" id="basic-form-phone" name="phone" type="Number"
                                placeholder="01000000000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-form-City">City</label>
                            <select class="form-select" id="basic-form-City" name="city"
                                aria-label="Default select example" onchange="cityChanged(this)">
                                <?php 
                                foreach($cities as $index => $valcity){
                                    echo '<option value='.$valcity["id"].'>'.$valcity["governorate_name_en"].'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-form-Area">Area</label>
                            <select class="form-select" id="basic-form-Area" name="area"
                                aria-label="Default select example">
                                <?php 
                                    foreach($areas as $kareas=>$valareas){
                                        echo '<option value='.$valareas["id"].'>'.$valareas["city_name_en"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Image</label>
                            <input class="form-control" name="company_images[]" type="file" accept="image/*" multiple>
                        </div>
                        <input type="hidden" name="routefor" value="company_adding">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add Category offcanvas  -->
    <div class="offcanvas offcanvas-end" id="offcanvasRight" tabindex="-1" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">add category</h5><button class="btn-close text-reset" type="button"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <form action="<?php echo "/company/controllers/Company.php";?>" method="POST">
            <div class="offcanvas-body">
                <div class="mb-3">
                    <label class="form-label" for="basic-form-name">Name</label>
                    <input class="form-control" name="name" id="basic-form-name" type="text" placeholder="Name">
                </div>
                <input type="hidden" name="routefor" value="categories_adding">
                <button class="btn btn-primary" type="submit">submit</button>
            </div>
        </form>
    </div>
    <?php } ?>
    <!-- modals for companies images preview -->
    <?php foreach($results as $key=>$value){ $comp_id=$value["id"];?>
    <div class="modal fade image-slide-copany" id="images<?php echo $comp_id ?>" tabindex="-1"
        aria-labelledby="verticallyCenteredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verticallyCenteredModalLabel"><?php echo $value["name"];?></h5>
                </div>
                <div class="modal-body">
                    <div class="carousel slide" id="carouselExampleControls<?php echo $comp_id ?>"
                        data-bs-ride="carousel" data-bs-interval="false">
                        <div class="carousel-indicators">
                            <?php $images=explode("___",$value["images"]);
                                $i=0;
                                foreach($images as $keyimg=>$valimg){
                                    if($valimg!=""){
                            ?>
                            <button <?php if($i==0){ echo 'class="active"'; }?> type="button"
                                data-bs-target="#carouselExampleControls <?php echo $comp_id ?>"
                                data-bs-slide-to="<?php echo $i; ?>" aria-current="true"
                                aria-label="Slide <?php echo $i+1;?>"></button>
                            <?php $i++;} } ?>
                        </div>
                        <div class="carousel-inner rounded">
                            <?php /* $images=explode("___",$value["images"]); */
                                $i=0;
                                foreach($images as $kimg=>$valimg){
                                    if($valimg!=""){
                            ?>
                            <div class="carousel-item <?php if($i==0){ echo 'active';$i++; } ?>">
                                <img class="d-block w-100 slide-images-company"
                                    src="<?php echo "/company/"."public/images/company/".$valimg ?>" alt="">
                            </div>
                            <?php } } ?>
                        </div>
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#carouselExampleControls<?php echo $comp_id ?>" data-bs-slide="prev">
                            <span class="prev-span-container">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button class="carousel-control-next" type="button"
                            data-bs-target="#carouselExampleControls<?php echo $comp_id ?>" data-bs-slide="next">
                            <span class="next-span-container">
                                <span class="sr-only">Next</span>
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- get the old supmitted data -->
    <?php
        $oldCategories=explode("___",$value["category"]); 
    ?>
    <!-- offcanvas for edit company -->
    <div class="offcanvas offcanvas-end  offcanvas-edit-forms" tabindex="-1"
        id="edit_compay_offcans<?php echo $comp_id ?>" aria-labelledby="edit_compay_offcansLabel<?php echo $comp_id ?>">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="edit_compay_offcansLabel<?php echo $comp_id ?>">edit company
                <?php $value["name"] ?></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="<?php echo "/company/controllers/Company.php";?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" for="basic-form-name<?php echo $comp_id ?>">Name</label>
                    <input class="form-control" id="basic-form-name<?php echo $comp_id ?>" name="name" type="text"
                        value="<?php echo $value["name"]; ?>" placeholder="Name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-form-Category<?php echo $comp_id ?>">Category</label>
                    <select class="form-select" name="category[]" id="basic-form-Category<?php echo $comp_id ?>"
                        aria-label="Default select example" multiple required>

                        <option value="none">none</option>
                        <?php 
                            foreach ($categories as $kcategory => $valcategory) {
                                // var_dump($oldCategories);
                                if(in_array($valcategory["name"],$oldCategories)){
                                    echo '<option value='.$valcategory["name"].' selected>'.$valcategory["name"]."</option>";
                                }else{

                                    echo '<option value='.$valcategory["name"].'>'.$valcategory["name"]."</option>";
                                }
                            }
                        ?>
                    </select>
                    <?php if(count($categories)==0){
                            echo '<span class="badge bg-danger">Add some categories </span>';
                            } 
                    ?>
                    <p class="badge bg-primary">to select more than one category press <span
                            class="badge bg-secondary">ctrl</span></p>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-form-phone<?php echo $comp_id ?>">Phone Number</label>
                    <input class="form-control" id="basic-form-phone<?php echo $comp_id ?>" name="phone" type="Number"
                        value="<?php echo $value["phone"]; ?>" placeholder="01000000000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-form-City<?php echo $comp_id ?>">City</label>
                    <select class="form-select" id="basic-form-City<?php echo $comp_id ?>" name="city"
                        aria-label="Default select example" onchange="cityChanged(this)">
                        <?php foreach($cities as $index => $valcities){
                                    if($value["city"]==$valcities["id"]){
                                        echo '<option value='.$valcities["id"].' selected>'.$valcities["governorate_name_en"].'</option>';
                                    }else{
                                        echo '<option value='.$valcities["id"].'>'.$valcities["governorate_name_en"].'</option>';
                                    }
                        } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-form-Area<?php echo $comp_id ?>">Area</label>
                    <select class="form-select" id="basic-form-Area<?php echo $comp_id ?>" name="area"
                        aria-label="Default select example">
                        <?php 
                            $CurrenAreas=$database->getareas($value["city"]);
                            foreach($CurrenAreas as $key=>$valareas){
                                if($valareas["id"]==$value["area"]){
                                    echo '<option value='.$valareas["id"].' selected>'.$valareas["city_name_en"].'</option>';
                                }else{
                                    echo '<option value='.$valareas["id"].'>'.$valareas["city_name_en"].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <?php
                ?>
                <div class="mb-3 images-preview">
                    <div class="row">
                        <?php
                                $i=0;
                                foreach($images as $kimg=>$valimg){
                                    if($valimg!=""){
                            ?>

                        <div class="col avatar avatar-4xl avatar-bordered me-4 image-preview-actions">
                            <input type="hidden" value="<?php echo $valimg; ?>">
                            <img class="rounded-circle" src="<?php echo "/company/"."public/images/company/".$valimg ?>"
                                alt="">
                            <div class="delete-image-company-floating" title="delete image">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                </svg>
                            </div>
                        </div>

                        <?php } } ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Image</label>
                    <input class="form-control" name="company_images[]" type="file" accept="image/*" multiple>
                </div>
                <input type="hidden" name="routefor" value="company_Editing">
                <input type="hidden" name="id" value="<?php echo $value["id"]; ?>">
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <?php } ?>

</body>
<script>
    setTimeout(function () {
        clear_alerts();
    }, 1000);

    function clear_alerts() {
        $(".alert").fadeOut("slow", function () {
            $(".alert").remove();
        })
    }

    function cityChanged(el) {
        $.ajax({
            type: "get",
            url: "/company/controllers/Company.php",
            data: {
                "id": el.value,
                "route": "get_areas"
            },
            success: function (response) {
                var result = JSON.parse(response);
                $(el).parent().next().find(".form-select").find("option").remove()
                // $("#basic-form-Area").find("option");
                result.forEach(element => {
                    $(el).parent().next().find(".form-select").append('<option value=' + element[
                        "id"] + '>' + element[
                        "city_name_en"] + '</option>');
                })
            }
        });
    }
    /* delete image of the company */
    $(".delete-image-company-floating").on("click", (el) => {
        var file_name = $(el.currentTarget).parent().find("input[type='hidden']").val()
        $.ajax({
            type: "POST",
            url: "/company/controllers/Company.php",
            data: {
                "route": "delete_img",
                "name": file_name
            },
            success: function (response) {
                if (response) {
                    $(el.currentTarget).parent().remove();
                }
            }
        });
    })
    /* delete confirmation of company */
    $(".btn.btn-danger.delete-btn").on("click", (el) => {
        el.preventDefault();
        if (confirm(`are you sure delete this company!`)) {
            $(el.currentTarget).parent().unbind('submit').submit();
        }
    })
</script>

</html>