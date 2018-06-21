<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Slides API PHP Quickstart');
    $client->setScopes(Google_Service_Slides::PRESENTATIONS); //PRESENTATIONS //PRESENTATIONS_READONLY
    $client->setAuthConfig('client_secret.json');
    $client->setAccessType('offline');
    $client->setRedirectUri('http://localhost:8888/google-slides/quickstart.php');
    $authUrl = $client->createAuthUrl();

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('credentials.json');
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        // $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        //$authCode = trim(fgets(STDIN));
        $authCode = isset($_GET['code']) ? $_GET['code'] : '';

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Slides($client);

// https://docs.google.com/presentation/d/1EAYk18WDjIG-zp_0vLm3CsfQh_i8eXc67Jo2O9C6Vuc/edit
$presentationId = '1EAYk18WDjIG-zp_0vLm3CsfQh_i8eXc67Jo2O9C6Vuc';
getSlide($service, $presentationId);

// $presentationLastId = createPresentation($service, $presentation);

getSlide($service, '10oAG5l6tqAXDh63_u-8FF6DRyALDtoPpEE90_5jPUko');

addSlide($service, '10oAG5l6tqAXDh63_u-8FF6DRyALDtoPpEE90_5jPUko');