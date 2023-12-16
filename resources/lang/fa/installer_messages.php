<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => 'نصب کننده ' . env('APP_NAME'),
    'next' => 'قدم بعدی',

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'خوش آمدید',
        'title'   => 'به نصب کننده خوش آمدید',
        'message' => 'به آسان نصب خوش آمدید ',
        'next' => 'بعدی'
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'title' => 'نیازمندی ها',
        'next' => 'بعدی'
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'title' => 'مجوز های دسترسی',
        'next' => 'بعدی'
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
      'menu' => [
        'templateTitle' => 'مرحله 3 | تنظیمات محلی',
        'title' => 'تنظیمات محلی',
        'desc' => 'لطفاً نحوه پیکربندی فایل <code>.env</code> برنامه‌ها را انتخاب کنید.',
        'wizard-button' => 'فرم تنظیمات',
        'classic-button' => 'ویرایشگر متن کلاسیک',
      ],
      'wizard' => [
        'templateTitle' => 'مرحله 3 | تنظیمات محلی | جادوگر هدایت شده',
        'title' => 'راهنمای <code>.env</code> جادوگر',
        'tabs' => [
            'environment' => 'محلی',
            'database' => 'پایگاه داده',
            'application' => 'نرم افزار',
        ],
        'form' => [
            'name_required' => 'نام محیط مورد نیاز است.',
            'app_name_label' => 'نام برنامه',
            'app_name_placeholder' => 'نام برنامه',
            'app_environment_label' => 'محیط برنامه',
            'app_environment_label_local' => 'محلی',
            'app_environment_label_developement' => 'توسعه',
            'app_environment_label_qa' => 'Qa',
            'app_environment_label_production' => 'تولید',
            'app_environment_label_other' => 'دیگر',
            'app_environment_placeholder_other' => 'وارد محیط خود شوید...',
            'app_debug_label' => 'اشکال زدایی برنامه',
            'app_debug_label_true' => 'صحیح',
            'app_debug_label_false' => 'غلط',
            'app_log_level_label' => 'سطح گزارش برنامه',
            'app_log_level_label_debug' => 'اشکال زدایی',
            'app_log_level_label_info' => 'اطلاعات',
            'app_log_level_label_notice' => 'اطلاع',
            'app_log_level_label_warning' => 'هشدار',
            'app_log_level_label_error' => 'خطا',
            'app_log_level_label_critical' => 'بحرانی',
            'app_log_level_label_alert' => 'هشدار',
            'app_log_level_label_emergency' => 'اضطراری',
            'app_url_label' => 'آدرس برنامه',
            'app_url_placeholder' => 'آدرس برنامه',
            'db_connection_failed' => 'نمی توان به پایگاه داده متصل شود.',
            'db_connection_label' => 'پایگاه داده متصل است',
            'db_connection_label_mysql' => 'mysql',
            'db_connection_label_sqlite' => 'sqlite',
            'db_connection_label_pgsql' => 'pgsql',
            'db_connection_label_sqlsrv' => 'sqlsrv',
            'db_host_label' => 'میزبان پایگاه داده',
            'db_host_placeholder' => 'میزبان پایگاه داده',
            'db_port_label' => 'پورت پایگاه داده',
            'db_port_placeholder' => 'پورت پایگاه داده',
            'db_name_label' => 'نام پایگاه داده',
            'db_name_placeholder' => 'نام پایگاه داده',
            'db_username_label' => 'نام کاربری پایگاه داده',
            'db_username_placeholder' => 'نام کاربری پایگاه داده',
            'db_password_label' => 'رمز پایگاه داده',
            'db_password_placeholder' => 'رمز پایگاه داده',

            'app_tabs' => [
                'more_info' => 'اطلاعات بیشتر',
                'broadcasting_title' => 'پخش، ذخیره سازی، جلسه، و &amp; صف',
                'broadcasting_label' => 'درایور پخش',
                'broadcasting_placeholder' => 'درایور پخش',
                'cache_label' => 'درایور کش',
                'cache_placeholder' => 'درایور کش',
                'session_label' => 'درایور جلسه',
                'session_placeholder' => 'درایور جلسه',
                'queue_label' => 'درایور صف',
                'queue_placeholder' => 'درایور صف',
                'redis_label' => 'درایور Redis',
                'redis_host' => 'میزبان Redis',
                'redis_password' => 'رمز عبور Redis',
                'redis_port' => 'پورت Redis',

                'mail_label' => 'ایمیل',
                'mail_driver_label' => 'درایور ایمیل',
                'mail_driver_placeholder' => 'درایور ایمیل',
                'mail_host_label' => 'میزبان ایمیل',
                'mail_host_placeholder' => 'میزبان ایمیل',
                'mail_port_label' => 'پورت ایمیل',
                'mail_port_placeholder' => 'پورت ایمیل',
                'mail_username_label' => 'نام کاربری ایمیل',
                'mail_username_placeholder' => 'نام کاربری ایمیل',
                'mail_password_label' => 'رمز عبور ایمیل',
                'mail_password_placeholder' => 'رمز عبور ایمیل',
                'mail_encryption_label' => 'رمزگذاری ایمیل',
                'mail_encryption_placeholder' => 'رمزگذاری ایمیل',

                'pusher_label' => 'Pusher',
                'pusher_app_id_label' => 'Pusher App Id',
                'pusher_app_id_palceholder' => 'Pusher App Id',
                'pusher_app_key_label' => 'Pusher App Key',
                'pusher_app_key_palceholder' => 'Pusher App Key',
                'pusher_app_secret_label' => 'Pusher App Secret',
                'pusher_app_secret_palceholder' => 'Pusher App Secret',
            ],
            'buttons' => [
                'setup_database' => 'تنظیمات پایگاه داده',
                'setup_application' => 'تنظیمات نرم افزار',
                'install' => 'نصب',
            ],
        ],
      ],
      'classic' => [
        'templateTitle' => 'مرحله 3 | تنظیمات محلی | ویرایشگر کلاسیک',
        'title' => 'ویرایشگر محیط کلاسیک',
        'save' => 'ذخیره .env',
        'back' => 'فرم تنظیمات',
        'install' => 'ذخیره و نصب',
      ],
        'title' => 'تنظیمات پیکربندی',
        'save' => 'ذخیره کردن .env',
        'success' => 'فایل .env برای شما ذخیره شد.',
        'errors' => 'ذخیره کردن فایل .env امکان پذیر نیست، لطفا آن را به صورت دستی ایجاد کنید.',
    ],

    'install' => 'نصب',

    /*
     *
     * Installed Log translations.
     *
     */
    'installed' => [
      'success_log_message' => 'نصب کننده با موفقیت به اتمام رسید ',
  ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'تمام شد',
        'templateTitle' => 'نصب به اتمام رسید',
        'finished' => 'اپلیکیشن با موفقیت نصب شد.',
        'migration' => 'Migration &amp; Seed Console Output:',
        'console' => 'خروجی کنسول برنامه:',
        'log' => 'ثبت گزارش نصب:',
        'env' => 'فایل نهایی .env:',
        'exit' => 'برای خروج اینجا را کلیک کنید',
    ],

    'updater' => [
      /*
       *
       * Shared translations.
       *
       */
      'title' => 'بروزرسانی',

      /*
       *
       * Welcome page translations for update feature.
       *
       */
      'welcome' => [
          'title'   => 'خوش آمدید به بروزرسانی',
          'message' => 'به نصب کننده بروزرسانی خوش آمدید',
      ],

      /*
       *
       * Welcome page translations for update feature.
       *
       */
      'overview' => [
          'title'   => 'بررسی اجمالی',
          'message' => '1 به روز رسانی وجود دارد.|بروز رسانی :number وجود دارد.',
          'install_updates' => 'نصب بروزرسانی',
      ],

      /*
       *
       * Final page translations.
       *
       */
      'final' => [
          'title' => 'تمام شده',
          'finished' => 'پایگاه داده برنامه با موفقیت به روز شد.',
          'exit' => 'برای خروج اینجا را کلیک کنید',
      ],

      'log' => [
          'success_message' => 'بروزرسانی با موفقیت به اتمام رسید ',
      ],
  ],
];
