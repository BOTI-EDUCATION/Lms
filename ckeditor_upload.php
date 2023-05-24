<?php

try {
  // File Route.
  $fileRoute = '/assets/schools/' . _school_alias . '/editor/';

  $fieldname = "file";

  // Get filename.
  $filename = explode(".", $_FILES[$fieldname]["name"]);

  // Validate uploaded files.
  // Do not use $_FILES["file"]["type"] as it can be easily forged.
  $finfo = finfo_open(FILEINFO_MIME_TYPE);

  // Get temp file name.
  $tmpName = $_FILES[$fieldname]["tmp_name"];

  // Get mime type. You must include fileinfo PHP extension.
  $mimeType = finfo_file($finfo, $tmpName);

  // Get extension.
  $extension = end($filename);

  // // Allowed extensions.
  // $allowedExts = array("mp4", "webm", "ogg", "gif", "jpeg", "jpg", "png", "svg", "blob", "txt", "pdf", "docx", "json", "html");

  // // Allowed mime types.
  // $allowedMimeTypes = array("video/mp4", "video/webm", "video/ogg", "image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/svg+xml");

  // // Validate file.
  // if (!in_array(strtolower($mimeType), $allowedMimeTypes) || !in_array(strtolower($extension), $allowedExts)) {
  //   throw new \Exception("File does not meet the validation.");
  // }

  // Generate new random name.
  $name = sha1(microtime()) . "." . $extension;
  $fullNamePath =  'assets/schools/' . _school_alias . '/editor/' . $name;

  // Check server protocol and load resources accordingly.
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
    $protocol = "https://";
  } else {
    $protocol = "http://";
  }

  // Save file in the uploads folder.
  move_uploaded_file($tmpName, $fullNamePath);

  // Generate response.
  $response = new \StdClass;
  $response->link = $protocol . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . $fileRoute . $name;

  // Send response.
  echo stripslashes(json_encode($response));
} catch (Exception $e) {
  // Send error response.
  echo $e->getMessage();
  http_response_code(404);
}
