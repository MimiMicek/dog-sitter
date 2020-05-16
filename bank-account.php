<?php

require_once 'header.php';

?>

<form class="sign-in" id="login" action="apis/api-save-account" method="POST">
    <div class="form-group">
        <label for="regNumber">Please enter your Account number (e.g. 1234-5678912345)*</label>
        <input type="number" name="regNumber" class="form-control" id="regNumber" placeholder="Reg. number" min="1000" max="9999" minlength="4" maxlength="4" required>
        <input type="number" name="accountNumber" class="form-control" id="accountNumber" placeholder="Account number" min="1000000000" max="9999999999" minlength="10" maxlength="10" required>
        <small id="paymentTerms" class="form-text text-muted">*Recurring payments at the end of each month (100kr Dog owners; 50kr Dog sitters)</small>
    </div>
    <div class="form-group">
        <button class="btn btn-secondary">Save account</button classform-group>
    </div>
</form>

<?php

require_once 'footer.php';

?>

