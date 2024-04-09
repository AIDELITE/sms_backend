<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Reagan Ajuna
 */

use GuzzleHttp\Client;

if (!function_exists('refresh_bal')) {

    function refresh_bal($call_type = false)
    {
        $CI = &get_instance();
        $CI->load->model('transaction_model');
        $CI->load->library('beyonic_transactions');
        $CI->db->trans_begin();


        $pending = $CI->transaction_model->get_pending_deposits();
        $request_ids = [];
        $sentepay_refs = [];
        $collection_requests = [];
        $feedback = [];

        foreach ($pending as $key => $value) {
            if (isset($value['request_id'])) {
                $request_ids[] = $value['request_id'];
            }
            if (isset($value['refNo'])) {
                $sentepay_refs[] = ['refNo' => $value['refNo'], 'ext_ref' => $value['ext_ref'], 'amount' => $value['amount'], 'user_id' => $value['user_id']];
            }
        }

        // sentepay 👇
        if (count($sentepay_refs) > 0) {
            foreach ($sentepay_refs as $value) {

                $headers = [
                    'X-Authorization' => '',
                ];
                $refNo = $value['refNo'];
                $ext_ref = $value['ext_ref'];
                $amount = $value['amount'];
                $user_id = $value['user_id'];

                $url = "{{baseUrl}}/transact/collect/status/'{$refNo}'/'{$ext_ref}'";

                $client = new Client(['headers' => $headers]);

                $response = $client->get($url);

                $status_code = $response->getStatusCode();

                if ($status_code == 200) { // successful
                    # update transactions table and user acc balance
                    $data = ['state_id' => 2, 'status' => 'successful'];
                    $CI->transaction_model->update_deposit_request(false, $data, $refNo);

                    $trans_data = [
                        'date_created' => date("Y-m-d H:i:s"),
                        'type' => 'DEPOSIT',
                        'CREDIT' => $amount,
                        'narrative' => 'Mobile Money Deposit',
                        'user_id' => $user_id
                    ];

                    $CI->transaction_model->create($trans_data);

                    # Deposit / Update acc balance
                    $res = $CI->transaction_model->deposit($amount, $user_id);
                    if (is_array($res)) {
                        $feedback = $res;
                    }
                }

                if ($status_code == 202) {
                    # Mark deposit transaction status as failed (state_id=3)
                    $data = ['state_id' => 3, 'status' => 'failed'];
                    $CI->transaction_model->update_deposit_request(false, $data, $refNo);
                }
            }
        }


        // Beyonic 👇 



        if (count($request_ids) > 0) {
            foreach ($request_ids as $value) {
                $collection_requests[] = $CI->beyonic_transactions->get_one_collection_request($value);
            }
        }

        foreach ($collection_requests as $request) {
            //$request->status = 'successful';
            //echo ($request);

            if ($request->status == 'successful') {
                # update transactions table and user acc balance
                $data = ['state_id' => 2, 'status' => 'successful'];
                $CI->transaction_model->update_deposit_request($request->id, $data);

                $trans_data = [
                    'date_created' => date("Y-m-d H:i:s"),
                    'type' => 'DEPOSIT',
                    'CREDIT' => $request->amount,
                    'narrative' => 'Mobile Money Deposit',
                    'user_id' => $request->metadata->user_id
                ];

                $CI->transaction_model->create($trans_data);

                # Deposit
                $res = $CI->transaction_model->deposit($request->amount, $request->metadata->user_id);
                if (is_array($res)) {
                    $feedback = $res;
                }
            }

            if ($request->status == 'failed') {
                # Mark deposit transaction status as failed (state_id=3)
                $data = ['state_id' => 3, 'status' => 'failed'];
                $CI->transaction_model->update_deposit_request($request->id, $data);
            }
        }

        if ($CI->db->trans_status() === FALSE) {
            $CI->db->trans_rollback();
            $feedback = [
                'message' => 'Something went wrong',
                'status' => false,
                'error' => true
            ];
            if ($call_type) {
                http_response_code(500);
            }
        } else {
            $CI->db->trans_commit();

            if ($call_type) {
                http_response_code(200);
            }
        }

        if ($call_type) {
            echo json_encode($feedback);
            return;
        }
    }
}
