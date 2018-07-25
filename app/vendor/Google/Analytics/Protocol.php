<?php

namespace App\Vendor\Google\Analytics;  

class Protocol {

	protected $commonParameters = array ();
	protected $version  = '1';
	protected $tid      = 'UA-88014780-1';
	protected $dh       = 'www.ohcasey.ru';
	protected $url      = 'http://www.google-analytics.com/collect';
	//protected $url    = 'http://127.0.0.1:12345/collect';

	public function __construct()
    {
		$this->commonParameters['v']    = $this->version;
		$this->commonParameters['tid']  = $this->tid;
		$this->commonParameters['dh']  = $this->dh;
	}

	public function track($parameters)
    {
        $parameters = array_merge($this->commonParameters, $parameters);
        $parametersString = http_build_query($parameters);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametersString);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        
        curl_close($ch);
	}
}
