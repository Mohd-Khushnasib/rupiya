<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Login Page">
    <meta name="keywords" content="Admin Panel, Login">
    <meta name="author" content="Your Company Name">
    <title>Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #6c63ff, #6f42c1);
            padding: 20px;
        }

        .login-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        .logo img {
            display: block;
            margin: 0 auto 20px;
            width: 80px;
            height: 80px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }

        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
            border-radius: 8px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #563d7c;
            border-color: #563d7c;
        }

        .text-muted {
            font-size: 14px;
        }

        .text-muted a {
            color: #6f42c1;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h4 class="text-center mb-4">CRM Login</h4>
            <form id="formAuthentication" method="post">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">Username</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
                            required>
                    </div>
                    <div class="col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" id="submit_button" type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery (Must be loaded first) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- SweetAlert2 (Correct Version) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $("#formAuthentication").submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ url('/admin-login') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json",
                    encode: true,
                    beforeSend: function () {
                        $("#submit_button").prop("disabled", true);
                    },
                    success: function (data) {
                        if (data.success === '1') {
                            Swal.fire({
                                title: "Login Successful",
                                text: "Redirecting to Dashboard...",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ url('/admin-dashboard') }}";
                            });
                        } else {
                            Swal.fire({
                                title: "Login Failed",
                                text: "Invalid credentials",
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ url('/login') }}";
                            });
                        }
                    },
                    error: function () {
                        Swal.fire("Something Went Wrong", "Please try again", "error");
                        $("#submit_button").prop("disabled", false);
                    }
                });
            });
        });
    </script>
</body>
</html>
