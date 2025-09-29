<?php

namespace App\Providers;

use DomainException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use App\Exceptions\UnprocessableEntityException;

class JwtService
{
    private string $key;
    private string $algorithm;
    private array $mensajesAuth;
    private string $labelMensaje;

    public function __construct()
    {
        $this->key = $this->validarJWTSecret(config('config.jwt_secret'));
        $this->algorithm = config('config.jwt_algoritmo');
        $this->mensajesAuth = config('messages.autenticacion');
        $this->labelMensaje = config('config.label_mensaje');
    }

    private function validarJWTSecret(?string $secret): string
    {
        if (empty($secret)) {
            throw new \RuntimeException('JWT_SECRET no está configurado');
        }

        return $secret;
    }

    public function encode(array $data): string
    {
        try {
            return JWT::encode($data, $this->key, $this->algorithm);
        } catch (\Exception $e) {
            throw new UnprocessableEntityException([
                $this->labelMensaje => $this->mensajesAuth['error_creacion']
            ]);
        }
    }

    public function validate(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            throw new UnprocessableEntityException([
                $this->labelMensaje => $this->mensajesAuth['expirado']
            ]);
        } catch (SignatureInvalidException $e) {
            throw new UnprocessableEntityException([
                $this->labelMensaje => $this->mensajesAuth['invalido']
            ]);
        } catch (DomainException | UnexpectedValueException $e) {
            throw new UnprocessableEntityException([
                $this->labelMensaje => $this->mensajesAuth['malformado']
            ]);
        } catch (\Exception $e) {
            throw new UnprocessableEntityException([
                $this->labelMensaje => $this->mensajesAuth['error']
            ]);
        }
    }
}
