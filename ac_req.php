<!DOCTYPE html>
<html>

<head>
<link rel = "stylesheet" href = "style.css">
<style>
/* form {border: 3px solid #f1f1f1;} */

button {
  background-color: #bc8ad4;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.container {
  padding: 16px;
}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

</style>
<title>DMG Portal - Account Request</title>
</head>

<body>


<h2> DMG Portal - New User Form </h2>

<form method="post" action="newuserscript.php" id="loginform">
<div class="container">
  <label for="New_username">Username: </label>
  <input type="text" name="New_username" required>

  <label for="New_password">Password: </label>
  <input type="password" name="New_password" required>
  <br>
  
  First Name:<br>
  <input type="text" name="n_first" required>
  <br>
  Surname:<br>
  <input type="text" name="n_surn" required>
  <br>
  Email Address:<br>
  <input type="text" name="n_email" required>
  <br><br>
  <button type="submit" value="Submit">Request</button>
</div>

<input type='hidden' name='approval' value='No'>

</form>
</body>
</html>