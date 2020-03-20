<?php

return [

    'check_token_error' => 'Authentication failed',
    'login_invalid' => 'Login is invalid, please login again',
    'account_forbidden' => 'This account has been disabled',
    'register_failed' => 'Register has failed',
    'register_success'=>'Register success',
    'user_password_require' => 'Password cannot be empty',
    'user_password_length' => 'Password length range 6 to 20',
    'trade_password_require' => 'Transaction password cannot be empty',
    'trade_password_length' => 'Transaction password length range 6 to 20',
    'login_success' => 'Login success',
    'user_mobile_not_found' =>'Account does not exist',
    'user_mobile_forbidden' => 'Account is disabled',
    'user_password_error' =>'Password error',
    'reset_user_password_success' => 'Reset login password succeeded',
    'new_password_same_old' =>'The new password cannot be the same as the old one',
    'user_nick_max' => 'Maximum nickname length is 25',
    'user_avatar_url' => 'The avatar is not a correct URL address',
    'user_mobile_same_old' => 'The new phone number cannot be the same as the old one',
    'reset_user_trade_password_success' => 'Reset transaction password succeeded',
    'feedback_content_max' => 'Content length up to 255',
    'feedback_content_require' => 'Content cannot be empty',
    'user_trade_password_error' => 'Wrong transaction password',


    'register_wallet_error' => 'Failed to register Wallet',
    'wallet_in_error' => 'Entry failure',
    'wallet_out_error' => 'Out of account failure',
    'wallet_address_require' => 'Please enter the address of receiving Wallet',
    'trans_amount_require' => 'Please enter the transfer amount',
    'wallet_address_not_found' => 'Wallet address does not exist',
    'transfer_min_amount' => 'Minimum transfer amount int:{:amount}',
    'wallet_amount_not_enough' => 'Insufficient wallet balance',
    'not_transfer_to_self' =>'Can\'t transfer to yourself',
    'transfer_min_amount_zero' => 'Transfer limit must be greater than 0',
    'wallet_error' => 'Wallet error',


    'flow_record_write_error' => 'request failure',
    'USE_TYPE_MINE' => 'Mine',
    'USE_TYPE_TRANS_FORM_SEND'=> 'Send wallet transfer in',
    'USE_TYPE_SEND_TO_HOLD'=> 'Transfer to hold wallet',
    'USE_TYPE_TRANS'=> 'Transfer',
    'USE_TYPE_SEND_TO_OTC'=> 'Transfer to OTC Wallet',
    'USE_TYPE_TRANS_FORM_OTC'=> 'Transfer from OTC Wallet',
    'USE_TYPE_INVITE_REWARD'=> 'Invitation Award',
    'USE_TYPE_REGISTER_REWARD'=> 'Registration Award',
    'USE_TYPE_READ_REWARD'=> 'Read Award',
    'USE_TYPE_OTC_TRADE'=> 'OTC buy',
    'USE_TYPE_OTC_RETURN'=> 'OTC return order',
    'USE_TYPE_OTC_UP'=> 'OTC sell',
    'USE_TYPE_TRANS_TO_SEND' =>'Transfer in to send Wallet',
    'USE_TYPE_UNDER_ORDER' => 'OTC sell undercarriage',
    'USE_TYPE_REWARD' => 'Reward',
    'user_transfer_in' => 'User:{:user_mobile} transfer into',
    'user_transfer_out' => 'Transfer to user {:user_mobile},Wallet address:{:wallet_address}',

    'flow_not_found' => 'Unknown source',
    'trans_not_found' => 'Transfer ',


    'conf_not_found' => 'Configuration information does not exist',



    'code_send_success' => 'Verification code sent successfully',
    'code_send_error' => 'Failed to send verification code',
    'code_require' => 'Verification code cannot be empty',
    'code_error' =>'Verification code error',



    'mobile_require' => 'Mobile number cannot be empty',
    'mobile_error' => 'Please input the correct mobile number',
    'mobile_unique' => 'Phone number already exists',


    'save_failed' => 'Save failed',
    'op_failed' => 'operation failed',
    'pull_success' => 'Submit successfully',
    'pull_failed' => 'Failure to submit',


    'set_payment_failed' => 'Collection method setting failed',
    'currency_require' => 'Please select currency',
    'sell_num_require' => 'Please enter the quantity of the hanging order',
    'payment_require' => 'Please select the collection or payment method',
        'currency_not_found' => 'Currency does not exist',
    'sell_not' => 'Currency prohibition',
    'buy_min_amount' => 'Minimum selling value {:amount}RMB curreny',
    'buy_max_amount' => 'Maximum selling value {:amount}RMB currency',
    'not_select_payment' => 'Please select the collection method',
    'payment_not_found' => 'Collection information does not exist',
    'payment_1_error' => 'Please improve your Alipay payment method.',
    'payment_5_error' => 'Please improve your wechat collection method',
    'payment_10_error' => 'Please improve your collection method of UnionPay',
    'not_payment' => 'Please improve the collection method first',
    'sell_failed' => 'Failed to list',
    'good_id_require' => 'Please select a good',
    'num_require' => 'Please enter purchase quantity',
    'good_not_found' => '',
    'buy_num_min' => 'The number of buy orders must be an integral multiple of {:num}',
    'buy_num_max' => 'Purchase at most:{:num}',
    'buy_limit_num' => 'Purchase quantity exceeds the remaining quantity,still left{:num} currency',
    'buy_failed' => 'Order failure',
    'good_not_sell' => 'This affidavit has been frozen',
    'not_under_order_has_trading' => 'There are unfinished orders, unable to take off the shelf',
    'under_failed' => 'Failure of landing gear',
    'buy_wallet_address' => 'Please enter your{:currency_name} wallet address',
    'alipay' => 'Alipay payment',
    'wx_pay' => 'WeChat payment',
    'bank_pay' => 'UnionPay payment',
    'order_nub_require' =>'Please select an order',
    'order_not_found' => 'Order does not exist',
    'payment_img_require' => 'Please upload the payment voucher',
    'appeal_require' => 'Appeal content cannot be empty',
    'appeal_max' => 'The maximum length of complaint content is 255 characters',
    'appeal_image_require' => 'Please upload the appeal voucher',
    'order_state_not_appeal' => 'Appeal cannot be initiated in current status',
    'order_state_not_set' => 'Cannot operate in current state',
    'not_buy_self' => 'Can\'t buy your own list',
    'order_already_appeal' => 'The order has been appealed, and the appeal cannot be continued',
    'sell_min_num' => 'The number of registered orders must be an integral multiple of {:num}',
    'buy_min_num' => 'The number of buy orders must be an integral multiple of {:num}',


    'user_level0' => 'Level 0',
    'user_level1' => 'Level 1',
    'user_level2' => 'Level 2',
    'user_level3' => 'Level 3',
    'user_level4' => 'Level 4',
    'user_level5' => 'Level 5',









];