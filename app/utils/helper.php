<?php 

/**
 *  @payload es un array con datos relacionados con las creedenciales del usuario
 * 
 *  @timeExpiration es el tiempo de expiración del token expresado en minutos
 */
function createToken($payload, $timeExpiration){

    $jwt = JWT::getInstance('F-JaNdRgUkXp2r5u8x/A?D(G+KbPeShVmYq3t6w9y$B&E)H@McQfTjWnZr4u7x!A');
    $payload = $payload;

    $expiration = time() + (60 * $timeExpiration); // 1 hora
    $token = $jwt->generateToken($payload, $expiration);

    return $token;       
}