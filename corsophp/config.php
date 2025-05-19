<?php

return [
  'mysql_host' => 'localhost',
  'mysql_user' => 'root',
  'mysql_password' => '',
  'mysql_db' => 'corsophp',
  'recordsPerPage' => 10,
  'orderByColums' => ['id', 'username', 'fiscalcode', 'email', 'age'],
  'recordsPerPageOptions' => [ 5,10,15,20,50,100],
  'search' => '',
  'maxLinks' => 11,
  'uploadDir' => 'avatar',
  'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
  'maxFileSize' => convertMaxUploadSizeToBytes()
];