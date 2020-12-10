<?php
header("Content-Security-Policy: default-src 'self';"); //Starts Content Security Policy to protect any remote code execution
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start(); //Starts session
?>