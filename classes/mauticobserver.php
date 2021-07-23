<?php
// This code is brought to you by AgattiTech


namespace local_mautic;

require_once($CFG->dirroot . '/local/guzzle/extlib/vendor/autoload.php');
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class mauticobserver {

    public static function enrolusercourse($event) {
        $myfile = fopen($CFG->dirroot . "/local/mautic/logs/enrolusercourse.txt", "w") or die("Unable to open file!");
		$txt = var_export($event, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$mauticurl = get_config('local_mautic', 'mauticurl');
		
		$httpClient = new \GuzzleHttp\Client();
		$request_options = [];
		$request_url = $mauticurl . '/form/submit?formId=2';
		
		try {
            $mautic_referer_id = (isset($_COOKIE['mautic_referer_id'])) ? $_COOKIE['mautic_referer_id'] : "";
            $mautic_session_id = (isset($_COOKIE['mautic_session_id'])) ? $_COOKIE['mautic_session_id'] : "";
            $mautic_device_id = (isset($_COOKIE['mautic_device_id'])) ? $_COOKIE['mautic_device_id'] : "";
            $mtc_id = (isset($_COOKIE['mtc_id'])) ? $_COOKIE['mtc_id'] : "";
            $mtc_sid = (isset($_COOKIE['mtc_sid'])) ? $_COOKIE['mtc_sid'] : "";

            $values = [
                'mautic_referer_id' => $mautic_referer_id,
                'mautic_session_id' => $mautic_session_id,
                'mautic_device_id' => $mautic_device_id,
                'mtc_id' => $mtc_id,
                'mtc_sid' => $mtc_sid,
            ];

            $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($values, $domain);
            $ip_address = $_SERVER['REMOTE_ADDR'];

            $request_options[RequestOptions::COOKIES] = $cookieJar;
            $request_options[RequestOptions::HEADERS]['X-Forwarded-For'] = $ip_address;
            
            $baseparams = array(
                'mauticform[formId]' => 2,
                'mauticform[return]' => $CFG->wwwroot,
                'mauticform[messenger]' => '1',
            );
            //TODO: getRequestData - get data from event and put it as mautic form data
            $formparams = array("mauticform[nome]" => "Joe");
            $request_options['form_params'] = array_merge($baseparams, $formparams);

            $response = $httpClient->request('POST', $request_url, $request_options);

        } catch (RequestException $request_exception) {
        
            $response = $request_exception->getResponse();
            $message = $request_exception->getMessage();
            
            return;
        }
		
    }

    function submitform($fields) {
        
    }
}
