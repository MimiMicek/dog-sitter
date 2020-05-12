<?php

require_once 'header.php';

?>
<form>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="fName">First name</label>
            <input type="text" class="form-control" id="fName">
        </div>
        <div class="form-group col-md-6">
            <label for="lName">Last name</label>
            <input type="text" class="form-control" id="lName">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address">
        </div>
        <div class="form-group col-md-4">
            <label for="cpr">CPR</label>
            <input type="text" class="form-control" id="cpr">
        </div>
        <div class="form-group col-md-2">
            <label for="phone">Phone number</label>
            <input type="number" class="form-control" id="phone">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="postalCode">Postal code</label>
            <input type="number" class="form-control" id="postalCode">
        </div>
        <div class="form-group col-md-4">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city">
        </div>
        <div class="form-group col-md-6">
            <label for="choice">I'm a</label>
            <select id="choice" class="form-control">
                <option selected>Choose...</option>
                <option>Dog owner</option>
                <option>Dog sitter</option>
            </select>
        </div>
    </div>
    <div class="form-row">
       <div class="form-group col-md-6">
           <label for="photo">Upload image of yourself and/or the dog</label>
           <input type="file" class="form-control-file" id="photo">
        </div>
        <div class="form-group col-md-6">
            <div class="mb-3">
                <label for="info">Information about yourself and/or the dog.</label>
                <textarea class="form-control " id="info" placeholder=""></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary">Register</button>
</form>

<?php

require_once 'footer.php';

?>

