<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
        <?php echo form_open_multipart('', array('role'=>'form', 'class'=>'form-horizontal', 'id'=>'form_login')); ?>
        <div class="card">
        <div class="header"><h2>Billing Details</h2></div>
        <div class="body table-responsive">
        <div class="row">
            <?php if ($this->session->userdata('logged_in')) : ?>
            <div class="col-md-5">
                <div class="form-group <?php echo form_error('billing_name') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_name', 'billing_name', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'billing_name', 'value'=>set_value('billing_name', (isset($billing['billing_name']) ? $billing['billing_name'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
                <div class="form-group <?php echo form_error('billing_address_1') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_address_1', 'address_1', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'billing_address_1', 'value'=>set_value('billing_address_1', (isset($billing['billing_address_1']) ? $billing['billing_address_1'] : '')), 'class'=>'form-control input-lg', 'type'=>'billing_address_1')); ?>
                    </div>
                </div>
                <div class="form-group <?php echo form_error('billing_city') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_city', 'city', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo form_input(array('name'=>'billing_city', 'value'=>set_value('billing_city', (isset($billing['billing_city']) ? $billing['billing_city'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
                <div class="form-group <?php echo form_error('billing_country') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_country', 'gender', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_dropdown('billing_country', array(
                          "GB" => "United Kingdom",
                          "US" => "United States",
                          "AF" => "Afghanistan",
                          "AL" => "Albania",
                          "DZ" => "Algeria",
                          "AS" => "American Samoa",
                          "AD" => "Andorra",
                          "AO" => "Angola",
                          "AI" => "Anguilla",
                          "AQ" => "Antarctica",
                          "AG" => "Antigua And Barbuda",
                          "AR" => "Argentina",
                          "AM" => "Armenia",
                          "AW" => "Aruba",
                          "AU" => "Australia",
                          "AT" => "Austria",
                          "AZ" => "Azerbaijan",
                          "BS" => "Bahamas",
                          "BH" => "Bahrain",
                          "BD" => "Bangladesh",
                          "BB" => "Barbados",
                          "BY" => "Belarus",
                          "BE" => "Belgium",
                          "BZ" => "Belize",
                          "BJ" => "Benin",
                          "BM" => "Bermuda",
                          "BT" => "Bhutan",
                          "BO" => "Bolivia",
                          "BA" => "Bosnia And Herzegowina",
                          "BW" => "Botswana",
                          "BV" => "Bouvet Island",
                          "BR" => "Brazil",
                          "IO" => "British Indian Ocean Territory",
                          "BN" => "Brunei Darussalam",
                          "BG" => "Bulgaria",
                          "BF" => "Burkina Faso",
                          "BI" => "Burundi",
                          "KH" => "Cambodia",
                          "CM" => "Cameroon",
                          "CA" => "Canada",
                          "CV" => "Cape Verde",
                          "KY" => "Cayman Islands",
                          "CF" => "Central African Republic",
                          "TD" => "Chad",
                          "CL" => "Chile",
                          "CN" => "China",
                          "CX" => "Christmas Island",
                          "CC" => "Cocos (Keeling) Islands",
                          "CO" => "Colombia",
                          "KM" => "Comoros",
                          "CG" => "Congo",
                          "CD" => "Congo, The Democratic Republic Of The",
                          "CK" => "Cook Islands",
                          "CR" => "Costa Rica",
                          "CI" => "Cote D'Ivoire",
                          "HR" => "Croatia (Local Name: Hrvatska)",
                          "CU" => "Cuba",
                          "CY" => "Cyprus",
                          "CZ" => "Czech Republic",
                          "DK" => "Denmark",
                          "DJ" => "Djibouti",
                          "DM" => "Dominica",
                          "DO" => "Dominican Republic",
                          "TP" => "East Timor",
                          "EC" => "Ecuador",
                          "EG" => "Egypt",
                          "SV" => "El Salvador",
                          "GQ" => "Equatorial Guinea",
                          "ER" => "Eritrea",
                          "EE" => "Estonia",
                          "ET" => "Ethiopia",
                          "FK" => "Falkland Islands (Malvinas)",
                          "FO" => "Faroe Islands",
                          "FJ" => "Fiji",
                          "FI" => "Finland",
                          "FR" => "France",
                          "FX" => "France, Metropolitan",
                          "GF" => "French Guiana",
                          "PF" => "French Polynesia",
                          "TF" => "French Southern Territories",
                          "GA" => "Gabon",
                          "GM" => "Gambia",
                          "GE" => "Georgia",
                          "DE" => "Germany",
                          "GH" => "Ghana",
                          "GI" => "Gibraltar",
                          "GR" => "Greece",
                          "GL" => "Greenland",
                          "GD" => "Grenada",
                          "GP" => "Guadeloupe",
                          "GU" => "Guam",
                          "GT" => "Guatemala",
                          "GN" => "Guinea",
                          "GW" => "Guinea-Bissau",
                          "GY" => "Guyana",
                          "HT" => "Haiti",
                          "HM" => "Heard And Mc Donald Islands",
                          "VA" => "Holy See (Vatican City State)",
                          "HN" => "Honduras",
                          "HK" => "Hong Kong",
                          "HU" => "Hungary",
                          "IS" => "Iceland",
                          "IN" => "India",
                          "ID" => "Indonesia",
                          "IR" => "Iran (Islamic Republic Of)",
                          "IQ" => "Iraq",
                          "IE" => "Ireland",
                          "IL" => "Israel",
                          "IT" => "Italy",
                          "JM" => "Jamaica",
                          "JP" => "Japan",
                          "JO" => "Jordan",
                          "KZ" => "Kazakhstan",
                          "KE" => "Kenya",
                          "KI" => "Kiribati",
                          "KP" => "Korea, Democratic People's Republic Of",
                          "KR" => "Korea, Republic Of",
                          "KW" => "Kuwait",
                          "KG" => "Kyrgyzstan",
                          "LA" => "Lao People's Democratic Republic",
                          "LV" => "Latvia",
                          "LB" => "Lebanon",
                          "LS" => "Lesotho",
                          "LR" => "Liberia",
                          "LY" => "Libyan Arab Jamahiriya",
                          "LI" => "Liechtenstein",
                          "LT" => "Lithuania",
                          "LU" => "Luxembourg",
                          "MO" => "Macau",
                          "MK" => "Macedonia, Former Yugoslav Republic Of",
                          "MG" => "Madagascar",
                          "MW" => "Malawi",
                          "MY" => "Malaysia",
                          "MV" => "Maldives",
                          "ML" => "Mali",
                          "MT" => "Malta",
                          "MH" => "Marshall Islands",
                          "MQ" => "Martinique",
                          "MR" => "Mauritania",
                          "MU" => "Mauritius",
                          "YT" => "Mayotte",
                          "MX" => "Mexico",
                          "FM" => "Micronesia, Federated States Of",
                          "MD" => "Moldova, Republic Of",
                          "MC" => "Monaco",
                          "MN" => "Mongolia",
                          "MS" => "Montserrat",
                          "MA" => "Morocco",
                          "MZ" => "Mozambique",
                          "MM" => "Myanmar",
                          "NA" => "Namibia",
                          "NR" => "Nauru",
                          "NP" => "Nepal",
                          "NL" => "Netherlands",
                          "AN" => "Netherlands Antilles",
                          "NC" => "New Caledonia",
                          "NZ" => "New Zealand",
                          "NI" => "Nicaragua",
                          "NE" => "Niger",
                          "NG" => "Nigeria",
                          "NU" => "Niue",
                          "NF" => "Norfolk Island",
                          "MP" => "Northern Mariana Islands",
                          "NO" => "Norway",
                          "OM" => "Oman",
                          "PK" => "Pakistan",
                          "PW" => "Palau",
                          "PA" => "Panama",
                          "PG" => "Papua New Guinea",
                          "PY" => "Paraguay",
                          "PE" => "Peru",
                          "PH" => "Philippines",
                          "PN" => "Pitcairn",
                          "PL" => "Poland",
                          "PT" => "Portugal",
                          "PR" => "Puerto Rico",
                          "QA" => "Qatar",
                          "RE" => "Reunion",
                          "RO" => "Romania",
                          "RU" => "Russian Federation",
                          "RW" => "Rwanda",
                          "KN" => "Saint Kitts And Nevis",
                          "LC" => "Saint Lucia",
                          "VC" => "Saint Vincent And The Grenadines",
                          "WS" => "Samoa",
                          "SM" => "San Marino",
                          "ST" => "Sao Tome And Principe",
                          "SA" => "Saudi Arabia",
                          "SN" => "Senegal",
                          "SC" => "Seychelles",
                          "SL" => "Sierra Leone",
                          "SG" => "Singapore",
                          "SK" => "Slovakia (Slovak Republic)",
                          "SI" => "Slovenia",
                          "SB" => "Solomon Islands",
                          "SO" => "Somalia",
                          "ZA" => "South Africa",
                          "GS" => "South Georgia, South Sandwich Islands",
                          "ES" => "Spain",
                          "LK" => "Sri Lanka",
                          "SH" => "St. Helena",
                          "PM" => "St. Pierre And Miquelon",
                          "SD" => "Sudan",
                          "SR" => "Suriname",
                          "SJ" => "Svalbard And Jan Mayen Islands",
                          "SZ" => "Swaziland",
                          "SE" => "Sweden",
                          "CH" => "Switzerland",
                          "SY" => "Syrian Arab Republic",
                          "TW" => "Taiwan",
                          "TJ" => "Tajikistan",
                          "TZ" => "Tanzania, United Republic Of",
                          "TH" => "Thailand",
                          "TG" => "Togo",
                          "TK" => "Tokelau",
                          "TO" => "Tonga",
                          "TT" => "Trinidad And Tobago",
                          "TN" => "Tunisia",
                          "TR" => "Turkey",
                          "TM" => "Turkmenistan",
                          "TC" => "Turks And Caicos Islands",
                          "TV" => "Tuvalu",
                          "UG" => "Uganda",
                          "UA" => "Ukraine",
                          "AE" => "United Arab Emirates",
                          "UM" => "United States Minor Outlying Islands",
                          "UY" => "Uruguay",
                          "UZ" => "Uzbekistan",
                          "VU" => "Vanuatu",
                          "VE" => "Venezuela",
                          "VN" => "Viet Nam",
                          "VG" => "Virgin Islands (British)",
                          "VI" => "Virgin Islands (U.S.)",
                          "WF" => "Wallis And Futuna Islands",
                          "EH" => "Western Sahara",
                          "YE" => "Yemen",
                          "YU" => "Yugoslavia",
                          "ZM" => "Zambia",
                          "ZW" => "Zimbabwe"
                        ), (isset($billing['billing_country']) ? $billing['billing_country'] : ''), 'id="gender" class="form-control input-lg"'); ?>
                    </div>
                </div>
                <?php endif;?>
                    <div class="billpaypal">
                      <div class="form-group <?php echo form_error('paypal') ? ' has-error' : ''; ?>">
                          <?php echo lang('paypal', 'paypal', array('class' => 'col-md-4 control-label')); ?>
                          <div class="col-md-8">
                              <?php echo form_input(array('name'=>'paypal', 'value'=>set_value('paypal', (isset($billing['paypal']) ? $billing['paypal'] : '')), 'class'=>'form-control input-lg')); ?>
                          </div>
                      </div>
                    </div>
                    <div class="billbtc">
                      <div class="form-group <?php echo form_error('btc_id') ? ' has-error' : ''; ?>">
                          <?php echo lang('btc_id', 'btc_id', array('class' => 'col-md-4 control-label')); ?>
                          <div class="col-md-8">
                              <?php echo form_input(array('name'=>'btc_id', 'value'=>set_value('btc_id', (isset($billing['btc_id']) ? $billing['btc_id'] : '')), 'class'=>'form-control input-lg')); ?>
                          </div>
                      </div>
                    </div>
                    <div class="billswipe">
                      <div class="form-group <?php echo form_error('card_number') ? ' has-error' : ''; ?>">
                          <?php echo lang('card_number', 'card_number', array('class' => 'col-md-4 control-label')); ?>
                          <div class="col-md-8">
                              <?php echo form_input(array('name'=>'card_number', 'value'=>set_value('card_number', (isset($billing['card_number']) ? $billing['card_number'] : '')), 'class'=>'form-control input-lg')); ?>
                          </div>
                      </div>
                      <div class="form-group <?php echo form_error('card_number') ? ' has-error' : ''; ?>">
                          <?php echo lang('card_exp', 'card_exp', array('class' => 'col-md-4 control-label')); ?>
                          <div class="col-md-4">
                              <?php echo form_input(array('name'=>'card_exp1', 'value'=>set_value('card_exp1', (isset($billing['card_exp1']) ? $billing['card_exp1'] : '')), 'class'=>'form-control input-lg')); ?>
                          </div>
                          <div class="col-md-4">
                              <?php echo form_input(array('name'=>'card_exp2', 'value'=>set_value('card_exp2', (isset($billing['card_exp2']) ? $billing['card_exp2'] : '')), 'class'=>'form-control input-lg')); ?>
                          </div>
                      </div>
                    </div>
            </div>

            <div class="col-md-5">
              <?php if ($this->session->userdata('logged_in')) : ?>
                <div class="form-group <?php echo form_error('billing_lastname') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_lastname', 'last_name', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'billing_lastname', 'value'=>set_value('billing_lastname', (isset($billing['billing_lastname']) ? $billing['billing_lastname'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
              <?php endif;?>
                <div class="form-group <?php echo form_error('billing_address_2') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_address_2', 'address_2', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'billing_address_2', 'value'=>set_value('billing_address_2', (isset($billing['billing_address_2']) ? $billing['billing_address_2'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
                <?php if ($this->session->userdata('logged_in')) : ?>
                  <div class="form-group <?php echo form_error('billing_state') ? ' has-error' : ''; ?>">
                      <?php echo lang('billing_state', 'state', array('class' => 'col-md-4 control-label')); ?>
                      <div class="col-md-8">
                          <?php echo form_input(array('name'=>'billing_state', 'value'=>set_value('billing_state', (isset($billing['billing_state']) ? $billing['billing_state'] : '')), 'class'=>'form-control input-lg')); ?>
                      </div>
                  </div>
                <div class="form-group <?php echo form_error('billing_method') ? ' has-error' : ''; ?>">
                    <?php echo lang('billing_method', 'billing_method', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_dropdown('billing_method', array('1'=>lang('payment_m_paypal'), '2'=>lang('payment_m_btc'),'3'=>lang('payment_m_swipe')), (isset($billing['billing_method']) ? $billing['billing_method'] : ''), 'id="billing_method" class="form-control input-lg"'); ?>
                    </div>
                </div>
                <div class="billswipe">
                  <div class="form-group <?php echo form_error('card_cvc') ? ' has-error' : ''; ?>">
                      <?php echo lang('card_cvc', 'card_cvc', array('class' => 'col-md-4 control-label')); ?>
                      <div class="col-md-8">
                          <?php echo form_input(array('name'=>'card_cvc', 'value'=>set_value('card_cvc', (isset($billing['card_cvc']) ? $billing['card_cvc'] : '')), 'class'=>'form-control input-lg')); ?>
                      </div>
                  </div>
                </div>
              <?php endif;?>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php if ($this->session->userdata('logged_in')) : ?>
                    <button type="submit" name="submit_form" id="submit_form" class="btn"><?php echo lang('action_save'); ?></button>
                <?php else : ?>
                    <button type="submit" name="submit_form" id="submit_form" class="btn"><?php echo lang('users_register'); ?></button>
                <?php endif; ?>
            </div>
        </div>
        <br>
        </div>
        <?php if($user['group_name'] == 'hosts' || !$this->ion_auth->is_non_admin()){?>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="header"><h2>My Balance: <?php echo $user['btc_balance'];?> BTC</h2></div>
              <div class="body table-responsive">
                <h3>Transaction History:</h3>
                  <table id="transac_table" class="display" cellspacing="0" width="100%"></table>
              </div>
            </div>
          </div>
        </div>
      <?php }; ?>
        <?php echo form_close(); ?>
    </div>
</div>
