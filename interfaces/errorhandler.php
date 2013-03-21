<?php

interface interfaces_ErrorHandler {
  public function handle_exception($exception, $errno = E_ERROR);
  public function handle_error($errno, $errstr, $errfile, $errline);
  public function handle_fatal_error();
}
