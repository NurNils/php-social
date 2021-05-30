<?php
/**
 * File: sockets.php
 * Sample socket creation
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}
 
echo "Socket created \n";
 
// Bind the source address
if( !socket_bind($sock, "127.0.0.1" , 5000) )
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not bind socket : [$errorcode] $errormsg \n");
}
 
echo "Socket bind OK \n";
 
if(!socket_listen ($sock , 10))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die("Could not listen on socket : [$errorcode] $errormsg \n");
}
 
echo "Socket listen OK \n";
 
echo "Waiting for incoming connections... \n";
 
//start loop to listen for incoming connections
while (true) 
{
    //Accept incoming connection - This is a blocking call
    $client =  socket_accept($sock);
     
    //display information about the client who is connected
    if(socket_getpeername($client , $address , $port))
    {
        echo "Client $address : $port is now connected to us. \n";
    }
     
    //read data from the incoming socket
    $input = socket_read($client, 1024000);
     
    $response = "OK .. $input";
     
    // Display output  back to client
    socket_write($client, $response);
    sleep(1000);
    socket_write($client, "sdfsdfsfdfsd");
}