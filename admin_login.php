<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/adLog_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
</head>
<style>

    </style>
<body>
     <div class="image-container">
        <img src="images/csab.png" alt="Image">
    </div>
     <h1>Colegio San Agustin - Bacolod</h1>
     <h3>Admin Login</h3>
<form class='login-form'>
  <div class="flex-row">
    <label class="lf--label" for="username">
      <i class="ri-user-line"></i>
    </label>
    <input id="username" class='lf--input' placeholder='Username' type='text'>
  </div>
  <div class="flex-row">
    <label class="lf--label" for="password">
      <i class="ri-lock-line"></i>
    </label>
    <input id="password" class='lf--input' placeholder='Password' type='password'>
  </div>
  <input class='lf--submit' type='submit' value='LOGIN'>
</form>
<a class='lf--note' href='#'>Forgot password?</a>
<a class='lf--note' href='register.php'>Register</a>
</body>
</html>