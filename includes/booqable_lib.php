<?php


class Booqable_lib {

	private $booqableTacexKey = '';
    private $tacexApiBaseUrl = 'https://tacex-hire.booqable.com/api/1/';
	private $apiGroup = '';
	private $params = [];

	public function init()
	{
		$this->booqableTacexKey = get_option('booqableTacexKey');
	}

    private function sendGetRequest($full_url)
    {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_PUT, 1);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
       curl_setopt($curl, CURLOPT_URL, $full_url);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $response = curl_exec($curl);
       curl_close($curl);

       return json_decode($response);
    }

	private function sendPostRequest($full_url, $payload = '')
    {
		$payload = json_encode($payload);
		// $payload = json_encode(['data'=>$payload]);
		// echo '<pre>';print_r( $full_url );echo '</pre>';die();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_INFILESIZE, filesize($file));
        // curl_setopt($curl, CURLOPT_INFILE, ($in = fopen($file, 'r')));
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_URL, $full_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $feed = curl_exec($curl);
        curl_close($curl);
        // fclose($in);
		// echo '<pre>';print_r( $feed );echo '</pre>';die();
        return true;
    }

	public function setAPIGroup($group)
	{
		$this->apiGroup = $group;
	}

	public function setParameters($params)
	{
		$this->params['api_key'] = $this->booqableTacexKey;
		$this->params = array_merge($this->params, $params);
	}

	public function initGetParams($operation = 'product_groups', $params = [])
	{
		$this->setAPIGroup($operation);
		$this->setParameters(
			array_merge([
				'page' => 1,
			], $params
		));
	}

	public function initPostParams($params = [])
	{
		$this->setAPIGroup('orders');
		$this->setParameters($params);
	}

    public function constructUrl($addnParams = "")
    {
		// if (empty($this->params) ) {
		// 	return $this->tacexApiBaseUrl . $this->apiGroup;
		// } else {
        	return $this->tacexApiBaseUrl . $this->apiGroup . $addnParams . '?' . http_build_query($this->params);
    	// }
    }

	public function getProducts()
	{
		$this->initGetParams('product_groups');
		$url = $this->constructUrl();
		$response = $this->sendGetRequest($url);
		return $this->parseResponse($response);
	}

	public function getOrders()
	{
		$this->initGetParams('orders');
		$url = $this->constructUrl();
		$response = $this->sendGetRequest($url);
		return $this->parseResponse($response);
	}

	public function getCustomers()
	{
		$this->initGetParams('customers');
		$url = $this->constructUrl();
		$response = $this->sendGetRequest($url);
		return $this->parseResponse($response);
	}

	public function updateOrderToConcept($orderId)
	{
		$this->initPostParams([]);
		$url = $this->constructUrl('/' . $orderId . '/concept');
		// echo '<pre>';print_r( $url );echo '</pre>';die();
		$response = $this->sendPostRequest($url, []);
	}

	public function submitOrder($params)
	{
		$this->initPostParams([]);
		$url = $this->constructUrl();
		// echo '<pre>';print_r( $url );echo '</pre>';die();
		$response = $this->sendPostRequest($url, $params);
		return $this->parseResponse($response);

	}

	public function parseResponse($response)
	{

		$metaArr = [];
		$mainArr = [];
		if ($this->apiGroup == 'product_groups') {
			$metaArr = $response->meta;
			$mainArr = $response->product_groups;
		} else if ($this->apiGroup == 'orders') {
			$metaArr = $response->meta;
			$mainArr = $response->orders;
		}

		return [
			'mainArr' => $mainArr,
			'metaArr' => $metaArr,
		];
	}
}
