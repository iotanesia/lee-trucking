<?php

namespace App\Services;

use Exception;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;
use Kreait\Firebase\Exception\Messaging\NotFound;

class FirebaseService
{
    /**
     * @var Firebase
     */
    protected $firebase;

    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(storage_path().'/json/firebase_credential.json');
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://mofing-ex-system.firebaseio.com');
    }

    public function sendNotif($request){
        $messaging = $this->firebase->createMessaging();
        $soundEffect = storage_path().'/sounds/puin_high.mp3';
        $message = CloudMessage::fromArray([
          'token' => $request['tokenFcm'],
          'notification' =>  $request['notif'],
          'data' => $request['data'], 
           'android' => [
                'notification' => [
                    'sound' => $soundEffect, 
                    'click_action' => 'OPEN_ACTIVITY_1'
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => $soundEffect,
                        'badge' => 1
                    ],
                ]
            ]
        ]);
        
        try {
            $messaging->send($message, $token);
        } catch (NotFound $e) {
            dd($e);
        } catch (InvalidArgument $e) {
            dd($e);
        }
    }
}