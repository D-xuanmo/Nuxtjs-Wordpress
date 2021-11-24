<?php

/**
 * 获取企业微信 access_token
 */
function get_access_token($reload) {
    $secret = get_option('xm_qywx_secret');

    if (!$secret) return null;

    $token = get_option('xm_qywx_access_token');
    if ($reload) $token = '';
    if (!$token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . $secret['qywx_id'] . "&corpsecret=" . $secret['qywx_secret']);
        $response = json_decode(json_encode(curl_exec($ch)));
        curl_close($ch);
        $data = json_decode($response);
        update_option('xm_qywx_access_token', $data->access_token);
        return $data->access_token;
    }
    return get_option('xm_qywx_access_token');
}

/**
 * 企业微信通知功能
 */
function qywx_notify($content) {
    $secret = get_option('xm_qywx_secret');

    // 请求参数
    $params = array(
        'touser'  => $secret['qywx_user_id'],
        'msgtype' => 'text',
        'agentid' => $secret['qywx_agent_id'],
        'text'    => array(
            'content' => $content
        )
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . get_access_token());
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);

    curl_close($ch);

    // token 过期重新获取
    if (json_decode($output)->errcode == 42001) {
        curl_setopt($ch, CURLOPT_URL, "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . get_access_token(true));
        curl_exec($ch);
        curl_close($ch);
    }
}
