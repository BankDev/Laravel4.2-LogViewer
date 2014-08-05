<?php

return array(

    'delete' => array(
        'modal' => array(
            'header' => 'คุณแน่ใจหรือไม่?',
            'body' => 'คุณยืนยันที่จะลบไฟล์นี้ใช่หรือไม่?'
            'btn' => array(
                'no' => 'ไม่ใช่',
                'yes' => 'ใช่',
            )
        ),
        'error' => 'เกิดข้อผิดพลาดในระหว่างที่กำลังจะลบไฟล์!',
        'success' => 'ลบไฟล์สำเร็จ',
        'btn' => 'ลบไฟล์นี้',
    ),
    'empty_file'  => ':sapi ไม่มีไฟล์ :date นี้!',
    'levels' => array(
        'all' => 'all',
        'emergency' => 'emergency',
        'alert' => 'alert',
        'critical' => 'critical',
        'error' => 'error',
        'warning' => 'warning',
        'notice' => 'notice',
        'info' => 'info',
        'debug' => 'debug',
    ),
    'no_log'  => ':sapi ไม่มีไฟล์ :date อยู่ในขณะนี้!';
    // @TODO Find out what sapi nginx, IIS, etc. show up as.
    'sapi'   => array(
        'apache' => 'Apache',
        'cgi-fcgi' => 'Fast CGI',
        'fpm-fcgi' => 'Nginx',
        'cli' => 'CLI',
    ),
    'title' => 'Laravel 4.2 LogViewer',

);
