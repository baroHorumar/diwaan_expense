<?php

$plaintext_password = "1234";

$hash =
    "$2y$10$b1PdBv2wRXteE0.99NYcW.h.k40mUfZ58uz0rBDi.oAvY8ntDJiQ.";
$verify = password_verify($plaintext_password, $hash);
if ($verify) {
    echo 'Password Verified!';
} else {
    echo 'Incorrect Password!';
}
