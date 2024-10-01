<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/reg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
</head>
<style>

</style>
<body>
     <div class="image-container">
        <img src="images/csab.png" alt="Image">
    </div>
     <h1>Colegio San Agustin - Bacolod</h1>
     <h3>Registration</h3>
<form class='reg-form'>
  <div class="flex-row">
    <label class="rf--label" for="fullname">
      <i class="ri-user-line"></i>
    </label>
    <input id="fullname" class='rf--input' placeholder='Full Name' type='text'>
  </div>
  <div class="flex-row">
    <label class="rf--label" for="email">
      <i class="ri-mail-line"></i>
    </label>
    <input id="email" class='rf--input' placeholder='Email' type='email'>
  </div>
  <div class="flex-row">
    <label class="rf--label" for="username">
      <i class="ri-user-line"></i>
    </label>
    <input id="username" class='rf--input' placeholder='Username' type='text'>
  </div>
  <div class="flex-row">
    <label class="rf--label" for="password">
      <i class="ri-lock-line"></i>
    </label>
    <input id="password" class='rf--input' placeholder='Password' type='password'>
  </div>
  <div class="flex-row">
    <label class="rf--label" for="role">
      <i class="ri-user-settings-line"></i>
    </label>
 <select id="role" class='rf--input'>
  <option value="" disabled selected>Select Role</option>
  <option value="teacher">Teacher</option>
  <option value="student">Student</option>
  <option value="admin">Admin</option>
</select>
  </div>
  <input class='rf--submit' type='submit' value='REGISTER'>
</form>
<a class='lf--note' href='admin_login.php'>Already have an Account? Login Here</a>

</body>
</html>