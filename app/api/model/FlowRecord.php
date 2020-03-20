<?php

namespace app\api\model;

class FlowRecord extends Base
{




    public static $user_id;


    public static $wallet_type;


    public static $flow_type;


    public static $use_type;


    public static $amount = 0;


    public static $trans_user_id = 0;


    /**
     *
     * @return bool
     */
    public static function createFlowRecord(): bool
    {
        $data = [];
        $data['flow_id'] = unique_str();
        $data['user_id'] = self::$user_id;
        $data['wallet_type'] = self::$wallet_type;
        $data['type'] = self::$flow_type;
        $data['use_type'] = self::$use_type;
        $data['amount'] = self::$amount;
        $data['trans_user_id'] = self::$trans_user_id;
        $data['create_time'] = now_date();
        $result = self::create($data);
        if (!$result) throw_exception(lang('flow_record_write_error'));
        return true;
    }


    /**
     * @param string $user_id
     * @param array $param
     */
    public static function lists(string $user_id,array $param): array
    {
        $model = self::where('user_id',$user_id);
        if (isset($param['wallet_type']) && $param['wallet_type']) {
            $model->where('wallet_type',$param['wallet_type']);
        }
        if (isset($param['use_type']) && $param['use_type']) {
            $model->where('use_type',$param['use_type']);
        }
        if (isset($param['type']) && $param['type']) {
            $model->where('type',$param['type']);
        }
        $data = $model->limit(page_offset(1),page_offset(2))->order('id desc')->select()->toArray();
        foreach ($data as $index => $item) {
            switch ($item['use_type']) {
                case self::USE_TYPE_MINE :
                    $data[$index]['describe'] = lang('USE_TYPE_MINE');
                    break;
                case self::USE_TYPE_TRANS_FORM_SEND :
                    $data[$index]['describe'] = lang('USE_TYPE_TRANS_FORM_SEND');
                    break;
                case self::USE_TYPE_SEND_TO_HOLD :
                    $data[$index]['describe'] = lang('USE_TYPE_SEND_TO_HOLD');
                    break;
                case self::USE_TYPE_TRANS :
                    $user  =  User::getUser($item['trans_user_id'],'user_mobile');
                    if ($user && !$user->isEmpty()) {
                            if ($item['type'] == self::FLOW_TYPE_IN) {
                                $data[$index]['describe'] = lang('user_transfer_in',['user_mobile'=>sub_mobile($user->user_mobile)]);
                            } else if ($item['type'] == self::FLOW_TYPE_OUT) {
                                    $userWalletAddress =   UserWallet::getUserWallet($item['trans_user_id'],'wallet_address')->wallet_address;
                                    $data[$index]['describe'] = lang('user_transfer_out',['user_mobile' => sub_mobile($user->user_mobile),'wallet_address'=> $userWalletAddress]);
                            } else {
                                $data[$index]['describe'] = lang('USE_TYPE_TRANS');
                            }
                    } else {
                        $data[$index]['describe'] = lang('trans_not_found');
                    }
                    break;
                case self::USE_TYPE_SEND_TO_OTC :
                    $data[$index]['describe'] = lang('USE_TYPE_SEND_TO_OTC');
                    break;
                case self::USE_TYPE_TRANS_FORM_OTC :
                    $data[$index]['describe'] = lang('USE_TYPE_TRANS_FORM_OTC');
                    break;
                case self::USE_TYPE_INVITE_REWARD :
                    $data[$index]['describe'] = lang('USE_TYPE_INVITE_REWARD');
                    break;
                case self::USE_TYPE_REGISTER_REWARD :
                    $data[$index]['describe'] = lang('USE_TYPE_REGISTER_REWARD');
                    break;
                case self::USE_TYPE_READ_REWARD :
                    $data[$index]['describe'] = lang('USE_TYPE_READ_REWARD');
                    break;
                case self::USE_TYPE_OTC_TRADE :
                    $data[$index]['describe'] = lang('USE_TYPE_OTC_TRADE');
                    break;
                case self::USE_TYPE_OTC_RETURN :
                    $data[$index]['describe'] = lang('USE_TYPE_OTC_RETURN');
                    break;
                case self::USE_TYPE_OTC_UP :
                    $data[$index]['describe'] = lang('USE_TYPE_OTC_UP');
                    break;
                case self::USE_TYPE_TRANS_TO_SEND:
                    $data[$index]['describe'] = lang('USE_TYPE_TRANS_TO_SEND');
                    break;
                case self::USE_TYPE_UNDER_ORDER:
                    $data[$index]['describe'] = lang('USE_TYPE_UNDER_ORDER');
                    break;
                case self::USE_TYPE_REWARD:
                    $data[$index]['describe'] = lang('USE_TYPE_REWARD');
                    break;
                default :
                    $data[$index]['describe'] = lang('flow_not_found');
                    break;
            }
        }
        return $data;
    }








}