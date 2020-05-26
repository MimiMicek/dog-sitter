<?php

require_once 'header.php';

?>
<form class="pt-2" id="register" action="apis/api-register.php" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="fName">First name</label>
            <input type="text" name="fName" class="form-control" id="fName" minlength="2" maxlength="40" value="Terrace" required>
        </div>
        <div class="form-group col-md-6">
            <label for="lName">Last name</label>
            <input type="text" name="lName" class="form-control" id="lName" minlength="2" value="Sorley" maxlength="40" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="abc@gmail.com" required>
        </div>
        <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" minlength="4" value="12345" maxlength="30" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" id="address" minlength="4"  value="Istedgade 15" maxlength="50" required>
        </div>
        <div class="form-group col-md-2">
            <label for="postalCode">Postal code</label>
            <input type="text" name="postalCode" class="form-control" id="postalCode" value="1000" minlength="4" maxlength="4" required>
        </div>
        <div class="form-group col-md-4">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" id="city" value="Copenhagen" minlength="2" maxlength="30" required>
        </div>

    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="cpr">CPR</label>
            <input type="text" name="cpr" class="form-control" id="cpr" value="1234567891" minlength="10" maxlength="10" required>
        </div>
        <div class="form-group col-md-2">
            <label for="phone">Phone number</label>
            <input type="number" name="phone" class="form-control" min="10000000" max="99999999" value="11111111" id="phone" minlength="8" maxlength="8" required>
        </div>
        <div class="form-group col-md-6">
            <label for="userType">I'm a</label>
            <select id="userType" name="userType" class="form-control" required>
                <option selected value="2">dog owner</option>
                <option value="3">dog sitter</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
           <label for="photo">Upload image of yourself and/or the dog</label><br>
           <img src="" style="width: 150px; height: 150px; margin-bottom: 20px" alt="Preview...">
           <input type="file" name="image" class="form-control-file" id="image" onchange="previewImage()" required>
        </div>
        <div class="form-group col-md-6">
            <div class="mb-3">
                <label for="info">Information about yourself and/or the dog.</label>
                <textarea class="form-control"
                          name="info" id="info"
                          placeholder="Doggo ipsum pupperino pupper fat boi noodle horse such treat, woofer doggorino bork.
                                       I am bekom fat heckin angery woofer fluffer I am bekom fat, fluffer mlem.
                                       Wow such tempt lotsa pats puggorino tungg, woofer the neighborhood pupper.
                                       Sub woofer puggorino blop, woofer.
                                       Shooberino very taste wow you are doing me a frighten puggorino doge, borkf vvv borkf."
                                       minlength="10"
                                       maxlength="1500"
                                       required></textarea>
            </div>
            <button name="uploadImage" type="submit" class="btn btn-secondary">Register</button>
        </div>
    </div>

</form>
<script>
    function previewImage() {
        let preview = document.querySelector('img');
        let file = document.querySelector('input[type=file]').files[0];
        let reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
</script>
<?php

require_once 'footer.php';

?>

