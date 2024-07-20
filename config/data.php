<?php

return ([
  "tokenExpiration" => 10, // minutes

  "paginate40" => 40,
  "paginate20" => 20,
  "paginate10" => 10,
  "cacheTime" => 3600 * 24,

  "loginType" => [
    "manual" => "MANUAL",
    "google" => "GOOGLE",
  ],

  "notifyChannels" => [
    "sms" => "SMS",
    "email" => "EMAIL",
  ],

  "emailChannels" => [
    'mailtrap', 'smtp', 'sendgrid'
  ],

  "logType" => [
    "activity" => "ACTIVITY",
    "email" => "EMAIL",
  ],

  "userType" => [
    "admin" => "ADMIN",
    "user" => "USER",
  ],

  "providers" => [
    "wallet" => "WALLET",
    "oxapay" => "OXAPAY",
  ],

  "userStatus" => [
    "inactive" => "INACTIVE",
    "active" => "ACTIVE",
    "suspended" => "SUSPENDED"
  ],

  "accountStatus" => [
    "pending" => "PENDING",
    "active" => "ACTIVE",
    "completed" => "COMPLETED"
  ],

  "activeStatus" => [
    "inactive" => "INACTIVE",
    "active" => "ACTIVE",
  ],

  "approval" => [
    "pending" => "PENDING",
    "approved" => "APPROVED",
    "rejected" => "REJECTED"
  ],

  "transactionType" => [
    "deposit" => "DEPOSIT",
    "refund" => "REFUND",
    "withdrawal" => "WITHDRAWAL",
    "invoice" => "INVOICE"
  ],

  "contentType" => [
    "faq" => "FAQ",
    "terms" => "TERM",
    "policy" => "POLICY",
    "blog" => "BLOG"
  ],

  "gender" => [
    "m" => "Male",
    "f" => "Female"
  ],

  "tokenFor" => [
    "passwordReset" => "PASSWORD_RESET",
    "emailVerification" => "EMAIL_VERIFICATION",
    "transaction" => "TRANSACTION",
    "adminLogin" => "ADMIN_LOGIN",
  ],

  "tokenTypes" => [
    "oneTimeToken" => "ONE-TIME-TOKEN",
    "defaultToken" => "BEARER-TOKEN",
  ],

  "dateFormat" => "M dS, Y",
  "dateFullFormat" => "Y-m-d H:i:s",
  "hourFormat" => "H:i:s",

  "currency" => "₦",

  "datatypes" => [
    'picture' => [
      'gif',
      'jpg',
      'bmp',
      'jpeg',
      'png',
      'svg',
      'webp'
    ],
    'audio' => [
      'mp3',
      'wma',
      'm4a',
      'ogg'
    ],
    'document' => [
      'doc',
      'docx',
      'txt',
      'pdf',
      'xls',
      'xlsx'
    ],
    'video' => [
      'mp4',
      'avi',
      '3pg',
      'mkv',
      'wmv'
    ],
    'pdf' => ['pdf'],
    'archive' => [
      'zip',
      '7z',
      'rar',
      'exe'
    ]
  ]
]);
