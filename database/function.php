<?php
// product_create function
function validateName($name)
{
    if ($name == null) {
        return 'Please enter product name';
    } else if (!preg_match('@[A-Z]@', $name)) {
        return 'product name must contain at least a capital letter.';
    } else if (!preg_match('@[a-z]@', $name)) {
        return 'product name must contain at least a small letter.';
    }
}

function validatePrice($price)
{
    if ($price == null) {
        return 'Please enter the price';
    } else if (!preg_match('/^[0-9]+(\\.[0-9]+)?$/', $price)) {
        return 'The price only can enter the number';
    }
}


// customer_create function
function validateUsername($username)
{
    if ($username == null) {
        return 'Please enter username';
    } else if (strlen($username) < 6) {
        return 'username must at least 6 character'; //return error msg
    }
}

function validateOrderPassword($password)
{
    if ($password == null) {
        return 'Please enter password';
    } else if (strlen($password) < 6) { //password length
        return 'password must at least 6 character'; //return error msg
    } else if (!preg_match('@[A-Z]@', $password)) {
        return 'password must contain at least a capital letter.';
    } else if (!preg_match('@[a-z]@', $password)) {
        return 'password must contain at least a small letter.';
    } else if (!preg_match('@[0-9]@', $password)) { //preg.checking format
        return 'password must contain at least a number.';
    }
}

function validatePassword($password, $inputconfirmPassword)
{
    if ($password == null) {
        return 'Please enter password';
    } else if ($inputconfirmPassword == null) {
        return 'Please enter confirm password';
    } else if (strlen($password) < 6) { //password length
        return 'password must at least 6 character'; //return error msg
    } else if (!preg_match('@[A-Z]@', $password)) {
        return 'password must contain at least a capital letter.';
    } else if (!preg_match('@[a-z]@', $password)) {
        return 'password must contain at least a small letter.';
    } else if (!preg_match('@[0-9]@', $password)) { //preg.checking format
        return 'password must contain at least a number.';
    } else if ($password != $inputconfirmPassword) { //not equal
        return 'Both Password & Confirm Password must be same.';
    }
}

function validateAge($year, $birthdate)
{
    if ($birthdate == null) {
        return 'Please select your Date And Birth';
    } else if (date('Y') - $year <= 18) {
        return 'You must at least 18 or above.';
    }
}

function validateGender($gender)
{
    if (empty($_POST["gender"])) {
        return 'Please Select Your gender.';
    }
}

function validateStatus($status)
{
    if (empty($_POST["status"])) {
        return 'Please Select Your status.';
    }
}



// customer_read function
function starsign($month, $day)
{
    if (($month == 3 && $day > 20) || ($month == 4 && $day < 20)) {
        echo "Aries";
    } else if (($month == 4 && $day > 19) || ($month == 5 && $day < 21)) {
        echo "Taurus";
    } else if (($month == 5 && $day > 20) || ($month == 6 && $day < 21)) {
        echo "Gemini";
    } else if (($month == 6 && $day > 20) || ($month == 7 && $day < 23)) {
        echo "cancer";
    } else if (($month == 7 && $day > 22) || ($month == 8 && $day < 23)) {
        echo "Leo";
    } else if (($month == 8 && $day > 22) || ($month == 9 && $day < 23)) {
        echo "Vrigo";
    } else if (($month == 9 && $day > 22) || ($month == 10 && $day < 23)) {
        echo "Libra";
    } else if (($month == 10 && $day > 22) || ($month == 11 && $day < 22)) {
        echo "Scorpion";
    } else if (($month == 11 && $day > 21) || ($month == 12 && $day < 22)) {
        echo "Sagittarius";
    } else if (($month == 12 && $day  > 21) || ($month == 1 && $day < 20)) {
        echo "Capricorn";
    } else if (($month == 1 && $day > 19) || ($month == 2 && $day < 19)) {
        echo "Aquarius";
    } else if (($month == 2 && $day > 18) || ($month == 3 && $day  < 21)) {
        echo "Pisces";
    }
}

function animalYear($year)
{
    $animalyear = array("Chicken", "Monkey", "Dog", "Pig", "Mouse", "Cow", "Tiger", "Rabbit", "Dragon", "Snake", "Horse", "Goat");

    $show = $year % 12;

    echo $animalyear[$show];
}

function sex($gender)
{
    if ($gender == "male") {
        return "<img src='images/male_symbol.png' width=30 alt='male'/>";
    } else {
        return "<img src='images/female_symbol.png' width=30 alt='female'/>";
    } if (empty($_POST["gender"])) {
        return 'Please Select Your gender.';
    }
}

function pro_img($image)
{
    if ($image == null){
        return "<img src='images/default_product_image.png' width=80 alt='prod_default'/>";
    }
    else if ($image == "prod_img") {
        return '<img src="uploads/<?php = $image ?> width=30 />"';
    }
}

//order create
/*function validateOrderusername($username){
    if (empty($_POST($username))){
        return 'Please select your Username.';
    }
}

function validateOrder($productID){
    if (empty($_POST($productID))) {
        return 'Please do not empty the selection products.';
    }
}*/

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}