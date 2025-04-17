<?php
    $login = 0;
    $invalid = 0;
    if($_SERVER['REQUEST_METHOD']=='POST'){
        include 'connect.php';
        $username=$_POST['Username'];
        $password=$_POST['Password'];        
        $sql = "Select * from `registration` where Username='$username' and Password='$password'";
        $result=mysqli_query($con,$sql);
        if($result){
            $num = mysqli_num_rows($result);
            if($num>0){
                $login = 1;
                session_start();
                $_SESSION['Username']=$username;
                header('location:index.php');
            }else{
                $invalid = 1;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-[#F28383] from-10% via-[#9D6CD2] via-30% to-[#481EDC] to-90% h-screen">
    <?php if($login): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success</strong> You are successfully logged in.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($invalid): ?>
        <script>
            alert('Invalid Credential');
            window.location.href='login.php';
        </script>
    <?php endif; ?>

    <main class="flex items-center justify-center h-screen">
        <div class="max-w-[960px] bg-[#00000050] grid grid-cols-2 items-center p-5 gap-20 rounded-2xl">
            <div>
                <img src="https://images.pexels.com/photos/3782228/pexels-photo-3782228.jpeg?auto=compress&cs=tinysrgb&w=600" alt="" class="rounded-2xl">
            </div>
            <div class="max-w-80 grid gap-5">
                <h1 class="text-5xl font-bold text-white">Login</h1>
                <p class="text-[#FFFFFFB3] text-2xl">Login your Account to visit our Website</p>

                <form id="loginForm" action="login.php" method="post" class="space-y-6 text-white">
                    <div class="relative">
                        <div class="absolute top-1 left-1 bg-white-medium rounded-full p-2 flex items-center justify-center text-blue-300">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="text" placeholder="Enter the Username" required class="w-80 py-2 px-12 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-1 focus:ring-blue-600 focus:drop-shadow-lg bg-[#FFFFFF30]" name="Username" autocomplete="off" id="username">
                    </div>
                    <div class="relative">
                        <div class="absolute top-1 left-1 bg-white-medium rounded-full p-2 flex items-center justify-center text-blue-300">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" required placeholder="Enter the password" class="w-80 py-2 px-12 rounded-full focus:bg-[#00000050] focus:ring-1 focus:outline-none focus:ring-blue-600 focus:drop-shadow-lg bg-[#FFFFFF40]" name="Password" id="password">
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-blue-400 to-cyan-200 w-80 rounded-full font-semibold py-2">
                        Login
                    </button>
                </form>

                <div class="text-[#FFFFFFB3] border-t border-[#FFFFFF40] pt-4 space-y-4 text-sm">
                    <p>Don't have an account? <a class="text-blue-300 cursor-pointer font-semibold hover:text-blue-400" href="./sign.php">SignUp</a></p>
                    <p>Forgot Password? <a class="text-blue-300 font-semibold hover:text-blue-400" href="forgot_password.php">Reset here</a></p>

                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            let isValid = true;

            // Reset any previous error styles
            username.classList.remove('border-red-500', 'border');
            password.classList.remove('border-red-500', 'border');

            if (username.value.trim() === '') {
                username.classList.add('border', 'border-red-500');
                isValid = false;
            }

            if (password.value.trim() === '') {
                password.classList.add('border', 'border-red-500');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Stop form submission
                alert('Please fill in both Username and Password.');
            }
        });
    </script>
</body>
</html>
