<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Base\Constants\Setting\Settings as SettingSlug;
use App\Base\Constants\Setting\SettingCategory;
use App\Base\Constants\Setting\SettingSubGroup;
use App\Base\Constants\Setting\SettingValueType;

class SettingsSeeder extends Seeder
{
    /**
     * List of all the settings_from_seeder to be created along with their details.
     *
     * @var array
     */
    protected $settings_from_seeder = [
        // SettingSlug::EMAIL => [
        //     'category'=>SettingCategory::NOTIFICATION,
        //     'value' => 1,
        //     'field' => SettingValueType::SELECT,
        //     'option_value' => '{"yes":1,"no":0}',
        //     'group_name' => null,
        // ],
        //     SettingSlug::SMS => [
        //     'category'=>SettingCategory::NOTIFICATION,
        //     'value' => 1,
        //     'field' => SettingValueType::SELECT,
        //     'option_value' => '{"yes":1,"no":0}',
        //     'group_name' => null,
        // ],

        SettingSlug::SERVICE_TAX => [
            'category'=>SettingCategory::TRIP_SETTINGS,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::ADMIN_COMMISSION_TYPE => [
            'category'=>SettingCategory::TRIP_SETTINGS,
            'value' => 30,
            'field' => SettingValueType::SELECT,
            'option_value' => '{"percentage":1,"fixed":2}',
            'group_name' => null,
        ],
            SettingSlug::ADMIN_COMMISSION => [
            'category'=>SettingCategory::TRIP_SETTINGS,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::WALLET_MIN_AMOUNT_FOR_TRIP => [
            'category'=>SettingCategory::WALLET,
            'value' => 50,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::WALLET_MIN_AMOUNT_TO_ADD => [
            'category'=>SettingCategory::WALLET,
            'value' => 20,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::WALLET_MAX_AMOUNT_TO_ADD => [
            'category'=>SettingCategory::WALLET,
            'value' => 5000,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
         SettingSlug::DRIVER_SEARCH_RADIUS => [
            'category'=>SettingCategory::TRIP_SETTINGS,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
         SettingSlug::MINIMUM_TIME_FOR_SEARCH_DRIVERS_FOR_SCHEDULE_RIDE => [
            'category'=>SettingCategory::TRIP_SETTINGS,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        // General category settings
        SettingSlug::LOGO => [
            'category'=>SettingCategory::GENERAL,
            'value' => null,
            'field' => SettingValueType::FILE,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::FAVICON => [
            'category'=>SettingCategory::GENERAL,
            'value' => null,
            'field' => SettingValueType::FILE,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::APP_NAME => [
            'category'=>SettingCategory::GENERAL,
            'value' => 'Tagxi',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::HEAD_OFFICE_NUMBER => [
            'category'=>SettingCategory::GENERAL,
            'value' => '+8299600007',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::HELP_EMAIL_ADDRESS => [
            'category'=>SettingCategory::GENERAL,
            'value' => 'sales@tagyourtaxi.com',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        SettingSlug::DRIVER_WALLET_MINIMUM_AMOUNT_TO_GET_ORDER => [
            'category'=>SettingCategory::WALLET,
            'value' => 1000,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        // Installation settings
        SettingSlug::GOOGLE_BROWSER_KEY => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => '**********************',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
        // SettingSlug::TWILLO_ACCOUNT_SID => [
        //     'category'=>SettingCategory::INSTALLATION,
        //     'value' => 'AC75a5048afaf14beaafec1a8c9e92e766',
        //     'field' => SettingValueType::TEXT,
        //     'option_value' => null,
        //     'group_name' => SettingSubGroup::TWILLO_SETTINGS,
        // ],
        // SettingSlug::TWILLO_AUTH_TOKEN => [
        //     'category'=>SettingCategory::INSTALLATION,
        //     'value' => 'b5d1f1cc4517066251673d53fad53734',
        //     'field' => SettingValueType::TEXT,
        //     'option_value' => null,
        //     'group_name' => SettingSubGroup::TWILLO_SETTINGS,
        // ],
        // SettingSlug::TWILLO_NUMBER => [
        //     'category'=>SettingCategory::INSTALLATION,
        //     'value' => '+12562779152',
        //     'field' => SettingValueType::TEXT,
        //     'option_value' => null,
        //     'group_name' => SettingSubGroup::TWILLO_SETTINGS,
        // ],
        SettingSlug::BRAINTREE_ENVIRONMENT => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => 'sandbox',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
        SettingSlug::ENABLE_BRAIN_TREE => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => '1',
            'field' => SettingValueType::SELECT,
            'option_value' => '{"yes":1,"no":0}',
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
            SettingSlug::BRAINTREE_MERCHANT_ID => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => 'pwc2hd46g93s4zy2',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
            SettingSlug::BRAINTREE_PUBLIC_KEY => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => 'gq22n23p93j65rs3',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
            SettingSlug::BRAINTREE_PRIVATE_KEY => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => '493fe24935e45beb46522e845a75d2ed',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
            SettingSlug::BRAINTREE_MASTER_MERCHANT => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => 'ganeshOwner',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
            SettingSlug::BRAINTREE_DEFAULT_MERCHANT => [
            'category'=>SettingCategory::INSTALLATION,
            'value' => 'tagxi',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => SettingSubGroup::BRAINTREE_SETTINGS,
        ],
        SettingSlug::REFERRAL_COMMISION_FOR_USER => [
            'category'=>SettingCategory::REFERRAL,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
          SettingSlug::REFERRAL_COMMISION_FOR_DRIVER => [
            'category'=>SettingCategory::REFERRAL,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],
         SettingSlug::MINIMUM_TRIPS_SHOULD_COMPLETE_TO_REFER_DRIVERS => [
            'category'=>SettingCategory::REFERRAL,
            'value' => 30,
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],

        SettingSlug::GOOGLE_MAP_KEY => [
            'category'=>SettingCategory::MAP_SETTINGS,
            'value' => 'AIzaSyBeVRs1icwooRpk7ErjCEQCwu0OQowVt9I',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
         SettingSlug::FIREBASE_DB_URL => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'https://your-db.firebaseio.com',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
         SettingSlug::FIREBASE_API_KEY => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-api-key',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
           SettingSlug::FIREBASE_AUTH_DOMAIN => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-auth-domain',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
         SettingSlug::FIREBASE_PROJECT_ID => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-firebase-project-id',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
         SettingSlug::FIREBASE_STORAGE_BUCKET => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-firebase-storage-bucket',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
        SettingSlug::FIREBASE_MESSAGIN_SENDER_ID => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-firebase-messaging-sender-id',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
        SettingSlug::FIREBASE_APP_ID => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-app-id',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ], 
         SettingSlug::FIREBASE_MEASUREMENT_ID => [
            'category'=>SettingCategory::FIREBASE_SETTINGS,
            'value' => 'your-firebase-measurement-id',
            'field' => SettingValueType::TEXT,
            'option_value' => null,
            'group_name' => null,
        ],

    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingDB = Setting::all();

        foreach ($this->settings_from_seeder as $setting_slug=>$setting) {
            $categoryFound = $settingDB->first(function ($one) use ($setting_slug) {
                return $one->name === $setting_slug;
            });

            $created_params = [
                        'name' => $setting_slug
                    ];

            $to_create_setting_data = array_merge($created_params, $setting);

            if (!$categoryFound) {
                Setting::create($to_create_setting_data);
            }
        }
    }
}
