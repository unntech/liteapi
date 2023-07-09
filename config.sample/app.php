<?php

defined('IN_LitePhp') or exit('Access Denied');

return[
    'ENVIRONMENT'   => 'DEV',  // 'DEV', 'PRO'
    'APP_DEBUG'     => true,  //生产环境：ENVIRONMENT 设为 PRO, APP_DEBUG 设为 false
    'name' => 'LiteApi',
    'authkey' => 'LitePhp_185622a8f4e2c72a9f75f8f5b8099259',
    'rsaKey'        => [
        'priv'   => 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALhg/mtc5nhCeqYIgNAMmAi/3xyQKIbgyK5McV2ovtpiiTc+3UONQcyVR+n0uRtgpXMZA1wplbh1ZhllxERqagf24QRhT25zpaBCVF08uyYqI+Dg/tHKbQoXGE+GcRqzw3aYQQf/PwWfwQ/9Gv39B9uyOxLluOghG2lZN82x1PU7AgMBAAECgYA2Rb6+JaNlhNQLaXdZRku+T5RCGSEEysfnnnLESfab2+NeErAYwUy8BrkbYcDXETTCU3uMtmTu3gfGtBD4voYk/6ZFwuJFViZTlim6Re3v+SE8Mou6vPkxV+06OC/udEdDOSvYcTz1kgOosJJEMnv/BpXhXwYoeibWE/xEoqGgeQJBAOyGYtpgQgBqDjKC8OiZ1No441W5ndX0MIiEmI8pCRXtkEVoPp0AkMSTBhDeqzLS/HRh0/tBTVkSiwNW6N0KPJ0CQQDHj3EdTRlO/uAE5G8UhauCrLHTaM3DZh78fQv/Vaz/T4elhU618tSurlddss4P8KsiVxppAc8/ow398ajS2dW3AkEA5shxZ/aIL/NLiwmsmqiOwabEWv7T/NFZEbufSACYNucn4DFI9tR4bPWv84HwtZScc8qIlh4vpHutXELOz+6PGQJBAJVMrLzWPLQMHX+rg6tf4hQOra/T/fVNRqtxxnMOHzKXxo1AMcYQWawihPx11JK6ZN55OioLj4k1rTcrADpXfPsCQCHI1mqrYCyZCihe8764h6IebdVWE9j8BaItf+ZweHG8wKxWytlJ8I1TI3rxgEprO1KoNzz7Y9frhUsoUss4wKQ=',
        'pub'    => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC4YP5rXOZ4QnqmCIDQDJgIv98ckCiG4MiuTHFdqL7aYok3Pt1DjUHMlUfp9LkbYKVzGQNcKZW4dWYZZcREamoH9uEEYU9uc6WgQlRdPLsmKiPg4P7Rym0KFxhPhnEas8N2mEEH/z8Fn8EP/Rr9/QfbsjsS5bjoIRtpWTfNsdT1OwIDAQAB',
        'thirdPub'=> 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC4YP5rXOZ4QnqmCIDQDJgIv98ckCiG4MiuTHFdqL7aYok3Pt1DjUHMlUfp9LkbYKVzGQNcKZW4dWYZZcREamoH9uEEYU9uc6WgQlRdPLsmKiPg4P7Rym0KFxhPhnEas8N2mEEH/z8Fn8EP/Rr9/QfbsjsS5bjoIRtpWTfNsdT1OwIDAQAB',
        'private_key_bits'=>1024
    ],
];