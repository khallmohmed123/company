<?php

?>
<style>
    .navbar-top {
        border-bottom: 1px solid #cbd0dd;
        dispaly: flex;
    }

    .collapse.navbar-collapse {
        display: flex !important;
        flex-basis: auto;
        padding-right: 2.5rem;
        padding-left: 2.5rem;
    }

    .search-box.d-none.d-lg-block {
        width: 25rem;
    }

    .search-container {
        font-size: .8rem;
        min-width: 20rem;
    }

    .form-control.form-control-sm.search-input.search.min-h-auto {
        min-height: calc(1.2em + 1rem + 2px);
        padding: 0.5rem 1rem;
        font-size: .8rem;
        border-radius: 0.375rem;
        padding-left: 2.5rem;
        padding-right: 2rem;
        border-radius: 0.375rem;
        box-shadow: none;
    }

    .svg-inline--fa.fa-search.fa-w-16.search-box-icon {
        position: absolute;
        color: #8a94ad;
        top: 50%;
        left: 1rem;
        transform: translateY(-48%);
        width: 1em;
        display: inline-block;
        font-size: inherit;
        height: 1em;
        overflow: visible;
        vertical-align: -0.125em;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-info .nav-link {
        padding: 0.75rem 0.5rem;
    }

    .user-info .nav-link .avatar.avatar-l {
        height: 2.5rem;
        width: 2.5rem;
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }

    .user-info .nav-link .avatar.avatar-l .rounded-circle {
        width: 100%;
        height: 100%;
    }

    .dropdown-profile {
        min-width: 18.3125rem;
        left: auto;
        right: -.5625rem;
        position: absolute;
    }

    .navbar-logo {
        display: flex;
        align-content: center;
        justify-content: center;
        align-items: center;
    }

    .nav-link.px-3 {
        cursor: pointer;
    }

    .pass-error {
        box-shadow: inset 0px 0px 13px 0px rgb(255 0 0 / 50%);
    }

    .msg-pass-miss-match.alert-danger {
        width: fit-content;
        padding: 10px;
    }

    .form-user-edit-details {
        padding: 20px;
    }
</style>
<nav class="navbar navbar-light navbar-top navbar-expand">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav navbar-nav-icons ms-auto flex-row">
            <li>
                <h6 class="mt-2"><?php echo $_SESSION["auth"]["name"]; ?></h6>
                <p><?php echo $_SESSION["auth"]["email"]; ?></p>
            </li>
            <li class="nav-item dropdown user-info">
                <a class="nav-link lh-1 px-0 ms-5" id="navbarDropdownUser" href="#" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-l"><img class="rounded-circle"
                            src="<?php echo Base_URL."public/images/user/".$_SESSION["auth"]["photo"]; ?>" alt=""></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0 dropdown-profile shadow border border-300"
                    aria-labelledby="navbarDropdownUser">
                    <div class="card bg-white position-relative border-0">
                        <div class="card-body p-0 overflow-auto scrollbar" style="height: 18rem;">
                            <div class="text-center pt-4 pb-3">
                                <h6 class="mt-2"><?php echo $_SESSION["auth"]["name"]; ?></h6>
                                <p><?php echo $_SESSION["auth"]["email"]; ?></p>
                                <p>Gender: <?php echo $_SESSION["auth"]["gender"]; ?></p>
                                <p>Birth Date : <?php echo $_SESSION["auth"]["birth_date"]; ?></p>
                            </div>
                            <ul class="nav d-flex flex-column mb-2 pb-1">
                                <li class="nav-item">
                                    <a class="nav-link px-3" data-bs-toggle="modal"
                                        data-bs-target="#user_details_change">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-settings me-2 text-900">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                            </path>
                                        </svg>
                                        account Settings
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer p-0 border-top">
                            <hr>
                            <div class="px-3">
                                <form action="<?php echo Base_URL."controllers/users.php";?>">
                                    <input type="hidden" name="routefor" value="logout">
                                    <button class="btn btn-phoenix-secondary d-flex flex-center w-100" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-log-out me-2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="modal fade" id="user_details_change" tabindex="-1" aria-labelledby="user_details_changeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="user_details_changeLabel">User Info</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times fs--1"></span>
                </button>
            </div>
            <form class="form-user-edit-details" action="<?php echo Base_URL."controllers/users.php";?>"
                method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-name-edit">Name</label>
                        <input class="form-control" id="basic-form-name-edit" name="name" type="text"
                            value="<?php echo $_SESSION["auth"]["name"]; ?>" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">change Image</label>
                        <input class="form-control" type="file" name="image-file-user">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-dob">Date of Birth</label>
                        <input class="form-control" id="basic-form-dob" name="birth_date" type="date"
                            value="<?php echo explode(" ",$_SESSION["auth"]["birth_date"])[0] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-gender">Gender</label>
                        <select class="form-select" name="gender" id="basic-form-gender"
                            aria-label="Default select example">
                            <option value="male" <?php if($_SESSION["auth"]["gender"] == "male") echo 'selected'; ?>>
                                Male</option>
                            <option value="female"
                                <?php if($_SESSION["auth"]["gender"] == "female") echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" onchange="pass_open(this)" name="passswitcher"
                            id="flexSwitchpassword" type="checkbox">
                        <label class="form-check-label" for="flexSwitchpassword">change password</label>
                    </div>
                    <div class="mb-3 password-container" style="display:none;">
                        <label class="form-label" for="password-user-profile-modify">Password</label>
                        <input class="form-control" id="password-user-profile-modify" name="pass" type="password"
                            placeholder="Password">
                        <label class="form-label" for="password-user-profile-modify2">retype Password</label>
                        <input class="form-control" id="password-user-profile-modify2" name="retypepass" type="password"
                            placeholder="Password">
                        <div class="msg-pass-miss-match alert-danger align-items-center" role="alert"
                            style="display:none">
                            the password doesn't matched
                        </div>
                    </div>
                </div>
                <input type="hidden" name="routefor" value="update-user-profile">
                <button class="btn btn-primary" style="float:right;" type="submit">confirm</button>
            </form>
        </div>
    </div>
</div>

<script>
    function pass_open(el) {
        if ($(el).prop("checked")) {
            $(".password-container").css("display", "block");
        } else {
            $(".password-container").css("display", "none");
        }
    }
    $("input").on("change", () => {
        $("#password-user-profile-modify").removeClass("pass-error");
        $("#password-user-profile-modify2").removeClass("pass-error");
        $(".msg-pass-miss-match").css("display", "none");
    })
    $("#user_details_change .btn.btn-primary").on("click", (el) => {
        el.preventDefault();
        if ($("#flexSwitchpassword").prop("checked")) {
            if ($("#password-user-profile-modify").val() != $("#password-user-profile-modify2").val() || $(
                    "#password-user-profile-modify2").val() == "" || $("#password-user-profile-modify").val() ==
                "") {
                $("#password-user-profile-modify").addClass("pass-error");
                $("#password-user-profile-modify2").addClass("pass-error");
                $(".msg-pass-miss-match").css("display", "block");
            } else {
                $("#user_details_change").find('form').unbind('submit').submit();
            }
        } else {
            $("#user_details_change").find('form').unbind('submit').submit();
        }
    })
</script>