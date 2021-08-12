<?php
// This code is brought to you by AgattiTech


namespace local_mautic;

require_once($CFG->dirroot . '/local/guzzle/extlib/vendor/autoload.php');
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class mauticobserver {

    public static function enrolusercourse($event) {
        global $CFG;

		$datalib = new \local_mautic\lib\datalib();

		$formlinks = $datalib->getformlinksfromevent($event);
		$myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_formlinks.txt", "w") or die("Unable to open file!");
		$txt = var_export($formlinks, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$fields = $datalib->getenrolfieldsfromformlinks($formlinks);
		$myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_fields.txt", "w") or die("Unable to open file!");
		$txt = var_export($fields, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$eventarray = array_values((array) $event);

		foreach($formlinks as $form) {
		    $preparedfields = self::preparefields($form, $fields, $eventarray);
		    self::submitform($preparedfields);
		}
    }

    private static function preparefields($form, $fields, $event) {
        global $CFG;
    
        $myfile = fopen($CFG->dirroot . "/local/mautic/logs/enrolusercourse.txt", "w") or die("Unable to open file!");
		$txt = var_export($event, true);
		fwrite($myfile, $txt);
		fclose($myfile);

		$baseparams = array(
            'mauticform[formId]' => $form['mauticformid'],
            'mauticform[return]' => $CFG->wwwroot,
            'mauticform[messenger]' => '1',
        );

        $formparams = array();

        foreach($fields[$form['id']] as $field) {
            $mauticfield = $field->mauticfield;
            $formparams["mauticform[$mauticfield]"] = $event[0][$field->moodlesource];
        }

		return array_merge($baseparams, $formparams);
    }
    
    private static function submitform($preparedfields) {
        global $CFG;
    
        $myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_COOKIE.txt", "w") or die("Unable to open file!");
		$txt = var_export($_COOKIE, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_SESSION.txt", "w") or die("Unable to open file!");
		$txt = var_export($_SESSION, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_SERVER.txt", "w") or die("Unable to open file!");
		$txt = var_export($_SERVER, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
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
            
            $request_options['form_params'] = $preparedfields;

            $response = $httpClient->request('POST', $request_url, $request_options);

        } catch (RequestException $request_exception) {
        
            $response = $request_exception->getResponse();
            $message = $request_exception->getMessage();
            
            return;
        }
    
    }
}
