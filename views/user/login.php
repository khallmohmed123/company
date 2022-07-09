<?php
    require_once("./../../config.php");
    session_start();
    if(isset($_SESSION["auth"]))
    {
        header("location:/company/views/");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    .form-login {
        box-shadow: 0px 0px 20px 10px #888888;
        padding: 65px;
        width: 50%;
        margin: 0px auto;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    .field-inputs-container {
        display: flex;
        align-content: center;
        justify-content: center;
        align-items: center;
    }

    .field-inputs-container .ico-container {
        width: 50px;
        display: flex;
        height: 100%;
        align-content: center;
        justify-content: center;
        align-items: center;
        background: #f3ca00;
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
        height: 40px;
    }

    .field-inputs-container .form-control {
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: 40px;
    }

    .col-sm-9 {
        padding: 0;
    }

    .submit-btn {
        float: right;
        background: #f3ca00;
        border: none;
        color: #000000;
        box-shadow: none;
        margin-right: 30px;
    }

    .submit-btn:hover {
        background: #d7b300;
        color: #000000;
    }

    .submit-btn:active,
    .submit-btn:focus,
    .submit-btn:focus-visible,
    .submit-btn:active:focus {
        float: right;
        background: #f3ca00;
        border: none;
        color: #000000;
        box-shadow: none;
        outline: unset;

    }

    body {
        background-image: url('<?php echo Base_URL.'public/images/background.jpg' ?>');
        background-size: cover;
        background-repeat: no-repeat;
        height: 100vh;
        font-family: 'Numans', sans-serif;
    }

    .footer-actions {
        color: #ffffff;
        display: block;
        margin-top: 100px;
        margin-left: 30px;
    }

    .footer-actions div {
        border-top: 1px solid rgba(0, 0, 0, 0.3);
    }

    .container {
        position: relative;
        top: 25%;
    }
</style>

<body>
    <div class="container">
        <form class="form-login" action="<?php echo Base_URL."controllers/users.php";?>" method="get">
            <?php 
            if(isset($_SESSION["error"]))
            {
                if($_SESSION["error"]["page"]=="login")
                {
                    echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                    unset($_SESSION["error"]);
                }
            }
            ?>
            <div class="row mb-3 field-inputs-container">
                <div class="ico-container" for="inputEmail3">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="col-sm-9">
                    <input class="form-control" id="inputEmail3" placeholder="username" name="email" type="email">
                </div>
            </div>
            <div class="row mb-3 field-inputs-container">
                <!-- <label class="col-sm-3 col-form-label" for="inputPassword3"> </label> -->
                <div class="ico-container">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="col-sm-9">
                    <input class="form-control" id="inputPassword3" placeholder="password" name="password"
                        type="password">
                </div>
            </div>
            <input type="hidden" name="routefor" value="signin">
            <button class="btn btn-primary submit-btn" name="submit" type="submit">login</button>
            <div class="footer-actions">
                <div>Don't have an account? <a href="signup.php">Sign Up</a></div>
            </div>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"
    integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(".ico-container").on("click", (el) => {
        $(el.currentTarget).parent().find("input").focus();
    })
    $("input").on("input", () => {
        $(".alert.alert-danger").remove();
    })
</script>

</html>