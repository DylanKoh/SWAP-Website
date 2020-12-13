<?php

if (!preg_match("/^[a-zA-Z0-9\s.,?!]*$/",htmlentities($_POST['orderCom']))) {
    //text written for comments are only allowed to have upper or lowercase letters,
    //whitespace, numbers ranging from 0-9, punctuations except quotation marks,
    //anything else will be rejected
    $commentErr = "Only numbers, punctuations except quotation marks, upper or lowercase letters are accepted!";
    echo $commentErr;
    $trueOrFalse= true;
}

//Requirements:
//include 'postOfferRegex';
//      if($trueOrFalse == true) {
//          return;
//      }