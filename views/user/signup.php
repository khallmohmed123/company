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
    <title>sign up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    .form-sign-up {
        box-shadow: 0px 0px 20px 10px #888888;
        padding: 65px;
        width: 50%;
        margin: 0px auto;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }

    body {
        background-image: url('<?php echo Base_URL.'public/images/background.jpg' ?>');
        background-size: cover;
        background-repeat: no-repeat;
        height: 100vh;
        font-family: 'Numans', sans-serif;
        color: #ffffff;
    }

    .container {
        position: relative;
        top: 10%;
    }

    .submit-btn {
        float: right;
        background: #f3ca00;
        border: none;
        color: #000000;
        box-shadow: none;
        /* margin-right: 30px; */
    }

    .submit-btn:hover {
        background: #d7b300;
        color: #000000;
    }

    .submit-btn:active,
    .submit-btn:focus,
    .submit-btn:focus-visible,
    .submit-btn:active:focus {
        /* float: right; */
        background: #f3ca00;
        border: none;
        color: #000000;
        box-shadow: none;
        outline: unset;

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
</style>

<body>
    <div class="container">
        <form class="form-sign-up" action="<?php echo Base_URL."controllers/users.php";?>" method="post"
            enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label" for="basic-form-name">Name</label>
                <input class="form-control" id="basic-form-name" name="name" type="text" placeholder="Name" required>
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="name")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-form-email">Email address</label>
                <input class="form-control" id="basic-form-email" name="email" type="email"
                    placeholder="name@example.com" required>
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="email")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-form-password">Password</label>
                <input class="form-control" id="basic-form-password" name="password" type="password"
                    placeholder="Password" required>
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="password")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-form-dob">Date of Birth</label>
                <input class="form-control" id="basic-form-dob" name="birth_date" type="date">
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="birth_date")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-form-gender">Gender</label>
                <select class="form-select" id="basic-form-gender" name="gender" aria-label="Default select example">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="gender")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" id="emolyee" type="radio" value="employee" name="user_type">
                    <label class="form-check-label mb-0" for="emolyee">employee</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="admin" type="radio" name="user_type" value="administrator"
                        checked="checked">
                    <label class="form-check-label mb-0" for="admin">administrator</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Image</label>
                <input class="form-control" name="user_image" type="file" accept="image/*">
                <?php if(isset($_SESSION["error"]))
                    {
                        if($_SESSION["error"]["page"]=="user"&&$_SESSION["error"]["target"]=="image")
                        {
                            echo '<div class="alert alert-danger">'.$_SESSION["error"]["message"].'</div>';    
                        }
                    }
                ?>
            </div>
            <input type="hidden" name="routefor" value="signup">
            <button class="btn btn-primary submit-btn" name="submit" type="submit">SignUp</button>

            <div class="footer-actions">
                <div>have an account? <a href="login.php">login</a></div>
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
    $("#basic-form-gender").on("input", (el) => {
        $(el.currentTarget).parent().find(".alert").remove()
    })
    $("#basic-form-dob").on("input", (el) => {
        $(el.currentTarget).parent().find(".alert").remove()
    })

    $("#basic-form-password").on("input", (el) => {
        $(el.currentTarget).parent().find(".alert").remove()
    })
    $("#basic-form-email").on("input", (el) => {
        $(el.currentTarget).parent().find(".alert").remove()
    })
    $("#basic-form-name").on("input", (el) => {
        $(el.currentTarget).parent().find(".alert").remove()
    })
</script>

</html>