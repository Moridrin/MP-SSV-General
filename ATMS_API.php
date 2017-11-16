<?php
namespace mp_ssv_general;

class ATMS_API
{
    // Mapping from legacy SSV field names to ATMS field names
    const SSV_ATMS_FIELD_MAP = [
        'email_address' => 'email',
        'user_email' => 'email',
        'member_email' => 'email',
        'name' => 'full_name',
        'display_name' => 'full_name',
        'address_city' => 'city',
        'address_postal_code' => 'zip_code',
        'address_street' => 'address',
        'at_ot_other' => 'type',
        'date_of_birth' => 'birthdate',
        'preffered_language' => 'prefered_lang',
        'registration_date' => 'joined_at',
        'postal_code' => 'zip_code',
        'contact_name' => 'name',
        'phone' => 'phone_number',
        'street' => 'address'
    ];
    
    public static function get($url, $token=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, get_option('atms_url') . '/api/v1/' . $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . get_option('atms_key')));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $bodyStr = curl_exec($ch);

        $headers = curl_getinfo($ch);
        curl_close($ch);

        if($headers['http_code']==200)
        {
            // Request succeeded
            return json_decode($bodyStr, true);
        }else
        {
            throw new \Exception('ATMS API responded with ' . $headers['http_code']);
        }
    }

    public static function put($url, $data)
    {
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, get_option('atms_url') . '/api/v1/' . $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . get_option('atms_key'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bodyStr = curl_exec($ch);

        $headers = curl_getinfo($ch);
        curl_close($ch);

        if($headers['http_code']==200)
        {
            // Request succeeded
            return json_decode($bodyStr, true);
        }else
        {
            throw new \Exception('ATMS API responded with ' . $headers['http_code']);
        }
    }

    public static function deleteCache($url)
    {
        $key = "atms-api-".$url;
        $group = "atms";

        delete_transient($key);
        wp_cache_delete($key, $group);
    }

    public static function cache($url, $expiration = 120, $persistent=true)
    {
        $key = "atms-api-".$url;
        $group = "atms";

        if($persistent)
        {
            $data = get_transient($key);
            if($data===false)
            {
                $data = self::get($url);
                set_transient($key, $data, $expiration);
                return $data;
            }
        }else
        {
            $data = wp_cache_get($key, $group);
            if($data===false)
            {
                $data = self::get($url);
                wp_cache_set($key, $data, $group, $expiration);
            }
        }

        return $data;
    }
}
