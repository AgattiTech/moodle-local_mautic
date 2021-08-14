<?php
// This code is brought to you by AgattiTech


namespace local_mautic;

require_once($CFG->dirroot . '/local/guzzle/extlib/vendor/autoload.php');
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Cookie\CookieJar;

class mauticobserver {

    public static function enrolusercourse($event) {
        global $CFG;

		$datalib = new \local_mautic\lib\datalib();

		$formevents = $datalib->getformeventsfromevent($event);
		$fields = $datalib->getformdatafromformevents($formevents);

		$values = $datalib->getsignificantvalues($event);

		foreach($formevents as $form) {
		    $preparedfields = self::preparefields($form, $fields, $values);
		    self::submitform($preparedfields);
		}
    }

    private static function preparefields($form, $fields, $values) {
        global $CFG;

		$baseparams = array(
            'mauticform[formId]' => $form['mauticformid'],
            'mauticform[return]' => $CFG->wwwroot,
            'mauticform[messenger]' => '1',
        );

        $formparams = array();

        foreach($fields[$form['id']] as $field) {
            $mauticfield = $field->mauticfield;
            $formparams["mauticform[$mauticfield]"] = $values[$field->moodlesource];
        }
        
        $myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_preparedfields.txt", "w") or die("Unable to open file!");
	    $txt = var_export($formparams, true);
	    fwrite($myfile, $txt);
	    fclose($myfile);

		return array_merge($baseparams, $formparams);
    }
    
    private static function submitform($preparedfields) {
        global $CFG;
		
		$mauticurl = get_config('local_mautic', 'mauticurl');
		
        $httpClient = new \GuzzleHttp\Client();

		$request_options = [];
		$request_url = $mauticurl . '/form/submit?formId=' . $preparedfields["mauticform[formId]"];
		$domain = preg_replace("(^https?://)", "", $mauticurl);
		
		try {
            $mautic_referer_id = (isset($_COOKIE['mautic_referer_id'])) ? $_COOKIE['mautic_referer_id'] : "";
            $mautic_session_id = (isset($_COOKIE['mautic_session_id'])) ? $_COOKIE['mautic_session_id'] : "";
            $mautic_device_id = (isset($_COOKIE['mautic_device_id'])) ? $_COOKIE['mautic_device_id'] : "";
            $mtc_id = (isset($_COOKIE['mtc_id'])) ? $_COOKIE['mtc_id'] : "";
            $mtc_sid = (isset($_COOKIE['mtc_sid'])) ? $_COOKIE['mtc_sid'] : "";
            
            if(isset($mtc_id)) {
                $preparedfields['mtc_id'] = $mtc_id;
            }

            $values = [
                'mautic_referer_id' => $mautic_referer_id,
                'mautic_session_id' => $mautic_session_id,
                'mautic_device_id' => $mautic_device_id,
                'mtc_id' => $mtc_id,
                'mtc_sid' => $mtc_sid,
            ];
            
            $myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_values.txt", "w") or die("Unable to open file!");
		    $txt = var_export($values, true);
		    fwrite($myfile, $txt);
		    fclose($myfile);

            $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($values, $domain);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            $myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_COOKIEJAR.txt", "w") or die("Unable to open file!");
		    $txt = var_export($cookieJar, true);
		    fwrite($myfile, $txt);
		    fclose($myfile);

            $request_options[RequestOptions::COOKIES] = $cookieJar;
            $request_options[RequestOptions::HEADERS]['X-Forwarded-For'] = $ip_address;
            $request_options[RequestOptions::HEADERS]['User-Agent'] = $_SERVER['HTTP_USER_AGENT'];
            
            $request_options['form_params'] = $preparedfields;

            $response = $httpClient->post($request_url, $request_options);

        } catch (RequestException $request_exception) {
        
            $response = $request_exception->getResponse();
            $message = $request_exception->getMessage();
            
            return;
        }
    
    }
}
