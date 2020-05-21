<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Jekyll v3.8.6">
        <title>Dog sitter</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/cover/">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        <meta name="theme-color" content="#563d7c">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
        <!-- Custom styles for this template -->
        <link href="css/header.css" rel="stylesheet">
    </head>
    <body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                <h3 class="masthead-brand">DOG SITTER™</h3>
                <nav class="nav nav-masthead justify-content-center">
                    <a class="nav-link active" href="index">Home</a>
                    <!--TODO If user is already logged in hide the Register link-->
                    <a class="nav-link" href="register">Register</a>
                    <a class="nav-link" href="login">Login</a>
                    <!--TODO Select users by user type (check with saved session) and limit if they see Find dog or find sitter-->
                    <a class="nav-link" href="find-dog">Find a dog</a>
                    <a class="nav-link" href="find-sitter">Find a sitter</a>
                    <a class="nav-link" href="my-profile">My profile</a>
                    <a class="nav-link" href="apis/api-logout.php">Logout</a>
                </nav>
            </div>
        </header>


