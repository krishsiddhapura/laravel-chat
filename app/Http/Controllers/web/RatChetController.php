<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class RatChetController extends Controller implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }
    public function onOpen(ConnectionInterface $conn)
    {
        // GETTING ALL QUERY PARAMS
        $queryParams = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryParams, $params);

        // VERIFYING TOKEN
        if(isset($params['token']) && !empty($params['token'])){
            $user = User::where('remember_token',$params['token'])->first();
            if(!empty($user)){
                $this->clients->attach($conn);
                $conn->resourceId = $user->id;

                foreach ($this->clients as $client) {
                    if($client->resourceId !== $user->id){
                        $msg = [
                            "type" => "connection_status",
                            "user_id" => $user->id,
                            "status" => "offline"
                        ];
                        $client->send(json_encode($msg));
                    }
                }
            }else{
                $conn->close(4001);
                return;
            }
        }else{
            $conn->close(4001);
            return;
        }
    }

    public function onMessage(ConnectionInterface $from, $msg){
        $msg = json_decode($msg,TRUE);
        if(isset($msg['type']) && $msg['type'] == "private" && isset($msg['receiver_id']) && !empty($msg['receiver_id'])){
            // PERSONAL CHAT MESSAGES
            foreach ($this->clients as $client) {
                if ($client->resourceId !== $from->resourceId && $client->resourceId === $msg['receiver_id']) {
                    $client->send(json_encode($msg));
                }
            }
        }

        if(isset($msg['type']) && $msg['type'] == "group"){
            // GROUP CHAT MESSAGE
            foreach ($this->clients as $client) {
                if ($client->resourceId !== $from->resourceId) {
                    $client->send(json_encode($msg));
                }
            }
        }

        if(isset($msg['type']) && $msg['type'] == "broadcast"){
            // BROADCAST MESSAGE
            foreach ($this->clients as $client) {
                if ($client->resourceId !== $from->resourceId) {
                    $client->send(json_encode($msg));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $queryParams = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryParams, $params);
        $user = User::where('remember_token',$params['token'])->first();
        foreach ($this->clients as $client) {
            if($client->resourceId !== $user->id){
                $msg = [
                    "type" => "connection_status",
                    "user_id" => $user->id,
                    "status" => "offline"
                ];
                $client->send(json_encode($msg));
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {

    }


}
