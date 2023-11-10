<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShippingRates;

class ShippingRatesController extends Controller
{

    public function LocalSelling($shop_id){
        $shippingRatesData = [
            ["Kosovë", "XK", "3"],
            ["Shqipëri", "AL", "5"],
            ["Maqedoni", "MK", "4"]
        ];

        foreach ($shippingRatesData as $data) {
            ShippingRates::create([
                'user_id' => auth()->user()->id,
                'shop_id' => $shop_id,
                'country' => $data[0],
                'country_code' => $data[1],
                'price' => $data[2],
                'enabled' => 'yes',
            ]);
        }
    }


    public function AutoCreate($shop_id)
    {

        $shippingRatesData = [
            ["Afghanistan", "AF", "4"],
            ["Albania", "AL", "8"],
            ["Algeria", "DZ", "12"],
            ["Andorra", "AD", "20"],
            ["Angola", "AO", "24"],
            ["Anguilla", "AI", "660"],
            ["Antarctica", "AQ", "10"],
            ["Antigua and Barbuda", "AG", "28"],
            ["Argentina", "AR", "32"],
            ["Armenia", "AM", "51"],
            ["Aruba", "AW", "533"],
            ["Australia", "AU", "36"],
            ["Austria", "AT", "40"],
            ["Azerbaijan", "AZ", "31"],
            ["Bahamas", "BS", "44"],
            ["Bahrain", "BH", "48"],
            ["Bangladesh", "BD", "50"],
            ["Barbados", "BB", "52"],
            ["Belarus", "BY", "112"],
            ["Belgium", "BE", "56"],
            ["Belize", "BZ", "84"],
            ["Benin", "BJ", "204"],
            ["Bermuda", "BM", "60"],
            ["Bhutan", "BT", "64"],
            ["Bolivia", "BO", "68"],
            ["Bonaire, Sint Eustatius and Saba", "BQ", "535"],
            ["Bosnia and Herzegovina", "BA", "70"],
            ["Botswana", "BW", "72"],
            ["Bouvet Island", "BV", "74"],
            ["Brazil", "BR", "76"],
            ["British Indian Ocean Territory", "IO", "86"],
            ["Brunei Darussalam", "BN", "96"],
            ["Bulgaria", "BG", "100"],
            ["Burkina Faso", "BF", "854"],
            ["Burundi", "BI", "108"],
            ["Cabo Verde", "CV", "132"],
            ["Cambodia", "KH", "116"],
            ["Cameroon", "CM", "120"],
            ["Canada", "CA", "124"],
            ["Cayman Islands", "KY", "136"],
            ["Central African Republic", "CF", "140"],
            ["Chad", "TD", "TCD"],
            ["Chile", "CL", "152"],
            ["China", "CN", "156"],
            ["Christmas Island", "CX", "162"],
            ["Cocos (Keeling) Islands", "CC", "166"],
            ["Colombia", "CO", "170"],
            ["Comoros", "KM", "174"],
            ["Congo", "CG", "178"],
            ["Congo (the Democratic Republic of the)", "CD", "180"],
            ["Cook Islands", "CK", "184"],
            ["Costa Rica", "CR", "188"],
            ["Croatia", "HR", "191"],
            ["Cuba", "CU", "192"],
            ["Curaçao", "CW", "531"],
            ["Cyprus", "CY", "196"],
            ["Czechia", "CZ", "203"],
            ["Côte d'Ivoire", "CI", "384"],
            ["Denmark", "DK", "208"],
            ["Djibouti", "DJ", "262"],
            ["Dominica", "DM", "212"],
            ["Dominican Republic", "DO", "214"],
            ["Ecuador", "EC", "218"],
            ["Egypt", "EG", "818"],
            ["El Salvador", "SV", "SLV"],
            ["Equatorial Guinea", "GQ", "226"],
            ["Eritrea", "ER", "232"],
            ["Estonia", "EE", "233"],
            ["Eswatini", "SZ", "SWZ"],
            ["Ethiopia", "ET", "231"],
            ["Falkland Islands (the)", "FK", "238"],
            ["Faroe Islands", "FO", "234"],
            ["Fiji", "FJ", "242"],
            ["Finland", "FI", "246"],
            ["France", "FR", "250"],
            ["French Guiana", "GF", "254"],
            ["French Southern Territories", "TF", "ATF"],
            ["Gabon", "GA", "266"],
            ["Gambia", "GM", "270"],
            ["Georgia", "GE", "268"],
            ["Germany", "DE", "276"],
            ["Ghana", "GH", "288"],
            ["Gibraltar", "GI", "292"],
            ["Greece", "GR", "300"],
            ["Greenland", "GL", "304"],
            ["Grenada", "GD", "308"],
            ["Guadeloupe", "GP", "312"],
            ["Guam", "GU", "316"],
            ["Guatemala", "GT", "320"],
            ["Guernsey", "GG", "831"],
            ["Guinea", "GN", "324"],
            ["Guinea-Bissau", "GW", "624"],
            ["Guyana", "GY", "328"],
            ["Haiti", "HT", "332"],
            ["Heard Island and McDonald Islands", "HM", "334"],
            ["Honduras", "HN", "340"],
            ["Hong Kong", "HK", "344"],
            ["Hungary", "HU", "348"],
            ["Iceland", "IS", "352"],
            ["India", "IN", "356"],
            ["Indonesia", "ID", "360"],
            ["Iran", "IR", "364"],
            ["Iraq", "IQ", "368"],
            ["Ireland", "IE", "372"],
            ["Isle of Man", "IM", "833"],
            ["Israel", "IL", "376"],
            ["Italy", "IT", "380"],
            ["Jamaica", "JM", "388"],
            ["Japan", "JP", "392"],
            ["Jersey", "JE", "832"],
            ["Jordan", "JO", "400"],
            ["Kazakhstan", "KZ", "398"],
            ["Kenya", "KE", "404"],
            ["Kiribati", "KI", "296"],
            ["Korea (the Democratic People's Republic of)", "KP", "408"],
            ["Korea (the Republic of)", "KR", "410"],
            ["Kuwait", "KW", "414"],
            ["Kyrgyzstan", "KG", "417"],
            ["Lao People's Democratic Republic", "LA", "418"],
            ["Latvia", "LV", "428"],
            ["Lebanon", "LB", "422"],
            ["Lesotho", "LS", "426"],
            ["Liberia", "LR", "430"],
            ["Libya", "LY", "434"],
            ["Liechtenstein", "LI", "438"],
            ["Lithuania", "LT", "440"],
            ["Luxembourg", "LU", "442"],
            ["Macao", "MO", "446"],
            ["Madagascar", "MG", "450"],
            ["Malawi", "MW", "454"],
            ["Malaysia", "MY", "458"],
            ["Maldives", "MV", "462"],
            ["Mali", "ML", "466"],
            ["Malta", "MT", "470"],
            ["Marshall Islands", "MH", "584"],
            ["Martinique", "MQ", "474"],
            ["Mauritania", "MR", "478"],
            ["Mauritius", "MU", "480"],
            ["Mayotte", "YT", "MYT"],
            ["Mexico", "MX", "484"],
            ["Micronesia (Federated States of)", "FM", "583"],
            ["Moldova", "MD", "498"],
            ["Monaco", "MC", "492"],
            ["Mongolia", "MN", "496"],
            ["Montenegro", "ME", "499"],
            ["Montserrat", "MS", "500"],
            ["Morocco", "MA", "504"],
            ["Mozambique", "MZ", "508"],
            ["Myanmar", "MM", "104"],
            ["Namibia", "NA", "516"],
            ["Nauru", "NR", "520"],
            ["Nepal", "NP", "524"],
            ["Netherlands", "NL", "528"],
            ["New Caledonia", "NC", "540"],
            ["New Zealand", "NZ", "554"],
            ["Nicaragua", "NI", "558"],
            ["Niger", "NE", "562"],
            ["Nigeria", "NG", "566"],
            ["Niue", "NU", "570"],
            ["Norfolk Island", "NF", "574"],
            ["Northern Mariana Islands", "MP", "580"],
            ["Norway", "NO", "578"],
            ["Oman", "OM", "512"],
            ["Pakistan", "PK", "586"],
            ["Palau", "PW", "PLW"],
            ["Palestine", "PS", "PSE"],
            ["Panama", "PA", "591"],
            ["Papua New Guinea", "PG", "598"],
            ["Paraguay", "PY", "PRY"],
            ["Peru", "PE", "604"],
            ["Philippines", "PH", "608"],
            ["Pitcairn", "PN", "612"],
            ["Poland", "PL", "616"],
            ["Portugal", "PT", "620"],
            ["Puerto Rico", "PR", "PRI"],
            ["Qatar", "QA", "QAT"],
            ["Réunion", "RE", "REU"],
            ["Romania", "RO", "ROU"],
            ["Russia", "RU", "RUS"],
            ["Rwanda", "RW", "RWA"],
            ["Saint Barthélemy", "BL", "652"],
            ["Saint Helena, Ascension and Tristan da Cunha", "SH", "SHN"],
            ["Saint Kitts and Nevis", "KN", "659"],
            ["Saint Lucia", "LC", "662"],
            ["Saint Martin", "MF", "663"],
            ["Saint Pierre and Miquelon", "PM", "666"],
            ["Samoa", "WS", "WSM"],
            ["San Marino", "SM", "SMR"],
            ["Sao Tome and Principe", "ST", "STP"],
            ["Saudi Arabia", "SA", "SAU"],
            ["Senegal", "SN", "SEN"],
            ["Serbia", "RS", "SRB"],
            ["Seychelles", "SC", "SYC"],
            ["Sierra Leone", "SL", "SLE"],
            ["Singapore", "SG", "SGP"],
            ["Sint Maarten", "SX", "SXM"],
            ["Slovakia", "SK", "SVK"],
            ["Slovenia", "SI", "SVN"],
            ["Solomon Islands", "SB", "SLB"],
            ["Somalia", "SO", "SOM"],
            ["South Africa", "ZA", "ZAF"],
            ["South Georgia and the South Sandwich Islands", "GS", "239"],
            ["South Sudan", "SS", "SSD"],
            ["Spain", "ES", "724"],
            ["Sri Lanka", "LK", "144"],
            ["Sudan", "SD", "SDN"],
            ["Suriname", "SR", "SUR"],
            ["Sweden", "SE", "SWE"],
            ["Switzerland", "CH", "756"],
            ["Syria", "SY", "SYR"],
            ["Taiwan", "TW", "TWN"],
            ["Tajikistan", "TJ", "TJK"],
            ["Tanzania", "TZ", "TZA"],
            ["Thailand", "TH", "THA"],
            ["Timor-Leste", "TL", "TLS"],
            ["Togo", "TG", "TGO"],
            ["Tokelau", "TK", "TKL"],
            ["Tonga", "TO", "TON"],
            ["Trinidad and Tobago", "TT", "TTO"],
            ["Tunisia", "TN", "TUN"],
            ["Turkey", "TR", "TUR"],
            ["Turkmenistan", "TM", "TKM"],
            ["Turks and Caicos Islands", "TC", "TCA"],
            ["Tuvalu", "TV", "TUV"],
            ["Uganda", "UG", "UGA"],
            ["Ukraine", "UA", "UKR"],
            ["United Arab Emirates", "AE", "784"],
            ["United Kingdom", "GB", "826"],
            ["United States of America", "US", "USA"],
            ["Uruguay", "UY", "URY"],
            ["Uzbekistan", "UZ", "UZB"],
            ["Vanuatu", "VU", "VUT"],
            ["Venezuela", "VE", "VEN"],
            ["Vietnam", "VN", "VNM"],
            ["Virgin Islands (British)", "VG", "VGB"],
            ["Virgin Islands (U.S.)", "VI", "VIR"],
            ["Wallis and Futuna", "WF", "WLF"],
            ["Western Sahara", "EH", "732"],
            ["Yemen", "YE", "YEM"],
            ["Zambia", "ZM", "ZMB"],
            ["Zimbabwe", "ZW", "ZWE"]            
        ];
        

        foreach ($shippingRatesData as $data) {
            ShippingRates::create([
                'user_id' => auth()->user()->id,
                'shop_id' => $shop_id,
                'country' => $data[0],
                'country_code' => $data[1],
                'price' => $data[2],
                'enabled' => 'yes',
            ]);
        }
    }



    public function shippingView(Request $request)
    {
        $search = $request->search;
        $user = auth()->user();
        $shopId = $user->managing_shop;
    
        if ($search) {
            $shipping = ShippingRates::where(function ($query) use ($user, $shopId, $search) {
                $query->where('user_id', $user->id)
                    ->where('shop_id', $shopId)
                    ->where(function ($query) use ($search) {
                        $query->where('country', 'LIKE', '%' . $search . '%')
                            ->orWhere('country_code', 'LIKE', '%' . $search . '%');
                    });
            })->paginate(10);
        } else {
            $shipping = ShippingRates::where('user_id', $user->id)
                ->where('shop_id', $shopId)
                ->paginate(10);
        }
    
        return view('shipping_countries', [
            'countries' => $shipping,
        ]);
    }
    


    public function update($country_id)
    {
        $shippingRate = ShippingRates::where('id', $country_id)
            ->where('user_id', auth()->user()->id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->first();
    
        if ($shippingRate) {
            // Toggle between 'yes' and 'no'
            $newEnabledValue = $shippingRate->enabled === 'yes' ? 'no' : 'yes';
    
            $shippingRate->update([
                'enabled' => $newEnabledValue
            ]);
        }
        return redirect()->back();
    }
    
    

    public function update_shipping_price(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'price' => 'required|numeric|min:0|max:100', // Use 'numeric' for price validation
            'country' => 'required|string|max:50',
        ]);
        
    
        // Retrieve the validated data
        $price = $request->price;
        $country = $request->country;
        
        // Find the shipping rate
        $shippingRate = ShippingRates::where('id', $country) // Use 'country' instead of 'id'
            ->where('user_id', auth()->user()->id)
            ->where('shop_id', auth()->user()->managing_shop)
            ->first();
        
        if ($shippingRate) {
            // Update the price and save the changes
            $shippingRate->price = $price;
            $shippingRate->save();
            return back()->with('success', trans('general.shipping_price_updated_success'));
        } else {
            return back()->with('error', trans('general.there_was_an_error'));
        }
    }
    



}
