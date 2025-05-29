<?php
session_start();

//get data from the form
$first_name = filter_inut(INPUT_POST, 'first_nmae');
//altrnative
//$first_name = $_POST['first_name];
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$program = filter_input(INPUT_POST, 'program');

require_once('database.php');

//Add the contact to the database

$query = 'INSERT INTO students
    (firstName, lastName, email, phone, program)
    VALUES
    (:firstName, :lastName, :email, :phone, :program)';
$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':email', $email);
$statement->bindValue(':phoneNumber', $phone_number);
$statement->bindValue(':program', $program);

$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;

//redirect to confirmation page

$url = "confirmation.php";
header("Location: " . $url);
die(); // releases add_contact.php from memory