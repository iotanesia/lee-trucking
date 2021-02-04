<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'firebase' => [
        'database_url' => env('FIREBASE_DATABASE_URL', 'https://mofing-ex-system.firebaseio.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'mofing-ex-system'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', '2411a4082e3bf1e22a1ff5415431e3efe2529eb3'),
        // replacement needed to get a multiline private key from .env 
        'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY', '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDIa6aR0HbR9/QO\njXc4k3aEXSSCwZsRDrnBhWIjwCuhLbTVhwz9C6MAp/kELaEBQdgdHpFyEqW4w81t\ndqy15rCEkn6Q73lc7aQOwkKCiulCXisDKi2C0AvrQJmvZijSQn0pDqJ5rkOykWxe\npTMgWxUP2CE8u0jMNe6XmHkUshpspvt8OoBva/YLV3QAKyN/NDBEyZ14xQ+KWZsj\nYzfbJv1bilPPcB+uLWAztXg+AKNHfKZ/+EMLqkQgcUB9MZR0WBS95vWB2fX1Lggu\n42gw8QoQ3gRysSl0n44kqGVgHGvvZ9dftn9PEeNnvcZYiIbTj3pe9gRe39Xhbm+x\nIAfGj22/AgMBAAECggEAIYeA8lwBxNbKibS5AUoKRdKfTRC+tHfWM73w0TJRGkHf\nQum5Doxn1LBFRKGtkdxGH3kBtCfSebqoH2v/MN9LIKKxceXeU+Gd1KpDHjvHEkdW\nwszHmF7d35mLHIDmy2Y8MiY0oE67HKCflKXmi78xItlxlgtu/lr3c30apI+3Q/3P\njkAajRoxFoGC04e2TgHOprKiNTp2vp2POJiPVO6I46tS17b+g+TRe84eNHqHfsD6\n9+rzwzaQcX/4fHak1en3oU1WjamFSZBFLuFUfyLjIaGbPZ0S7+7GeNABPUFzgrsT\nqQjxpIXD4LEHU+6nAkBJqBfhVEFJTlu39HIlvJ7XAQKBgQDzySBFBSMesrTERuYy\npvhIv06TMvsmbDtGEo/3Yg07u9gyhXINGeqhIx15lJ9oVIA89kjLJnibpAw0BIKr\nY+3AuwTkjo6Pt+Q6rgQjndKUaOUrQ0NXFm6e4qgQKZ3wuAyv3mP8rt5VmVx4TJ0n\n8Vahin376L8W/0cZh0AvPrFCoQKBgQDSdk8rUqNKlNDLEIkrvN2+yekKRp0UtWnw\nzQY/gRbRiwzaVIDefWTlt2jIVm0vhlrLxSJahKk2ujatehrAzBO2p7SV4IUkml5J\nLihRi8FKTtrBG29NoL4v/cOTwlbc/4m0EHwyLVZhqma4rtmmZ4Pwox6ei+vwrA2Z\nN+2eF980XwKBgQCsDInFrDZ4984zp4XWwZgAX2No51Xwa/n5HiiUgpKsUYnnPd6b\n+GpqhonKwGUwEFLgsz/0f5Ym4cPrAjPmGYQ1vMdbNnDOZQ2hjCZHrJ5tNwplWfcw\ngxhW8ceCgJyCwqHRjLYsIo2vA6kLDabsBZsKLt9y5XPXGxpmndlifnBDgQKBgGe1\n40d+/naXJNicOmwyMrd8ufjVWTzGNQFzltueWkdCFUBVmfeJpTnKCrmGVWGw/Hcj\nms0uSFBurNkJrbtKFYDR3ZxpulKSO1omg7nDe6mJeCTmxw9i4bW2gDUE9FcNGaeA\n5CK50rJoDRx/FUP6CLuBNmp8mvTLZBwXwmFAdD5TAoGAWHzQy+7ybz4weMFBUVD6\nZZ/BzR3CBK/abrI3NQvcUOqmsWBRIS42V3S/LuVhfLlgqO17ffuYCgVc4fvSdq04\n/a3Y8ZZuhmMsf0oVLWpcTDexaQardJVLiwdrKxRUYK6nw/PkvtogB4BU0Vn2mkzb\nHDv2wqL2h/TlwhRVUtuQt6o=\n-----END PRIVATE KEY-----\n')),
        'client_email' => env('FIREBASE_CLIENT_EMAIL', 'firebase-adminsdk-fmmj1@mofing-ex-system.iam.gserviceaccount.com'),
        'client_id' => env('FIREBASE_CLIENT_ID', '116468125614713187385'),
        'client_x509_cert_url' => env('FIREBASE_CLIENT_x509_CERT_URL', 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fmmj1%40mofing-ex-system.iam.gserviceaccount.com'),
    ]

];
