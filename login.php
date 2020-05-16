<?php

require_once 'header.php';

?>

<form id="login" action="apis/api-login.php" method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" id="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
    </div>
    <div class="form-group">
        <button class="btn btn-secondary">Login</button classform-group>
    </div>
</form>

<?php

require_once 'footer.php';

?>

