<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Login</h2>
    <form id="loginForm">
        <input type="email" id="email" name="email" placeholder="Email" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p id="errorMessage" style="color: red;"></p>

    <script>
        var baseurl = "{{ config('app.url') }}";
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                
                let email = $('#email').val();
                let password = $('#password').val();

                $.ajax({
                    url: `${baseurl}/api/login`,
                    type: "POST",
                    data: { email: email, password: password },
                    success: function (response) {
                        localStorage.setItem("authToken", response.token);
                        window.location.href = "{{ route('dashboard') }}"; 
                    },
                    error: function () {
                        $('#errorMessage').text("Invalid email or password");
                    }
                });
            });
        });
    </script>

</body>
</html>
