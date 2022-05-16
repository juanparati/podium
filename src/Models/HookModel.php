<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Requests\HookRequest;

/**
 * @see https://developers.podio.com/doc/hooks
 */
class HookModel extends ModelBase
{
    public function init(): void
    {
        $this->registerProp('hook_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
    }


    /**
     * Delete hook.
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function delete(): static
    {
        if (!$this->hook_id)
            throw new \RuntimeException('Hook Id is required');

        if ($this->request()->delete($this->hook_id))
            $this->reset();

        return $this;
    }


    /**
     * Create hook.
     *
     * @param string|int $appId
     * @param string $refType
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function create(string|int $appId, string $refType = 'app') : static {
        $hook = $this->request()
            ->create($appId, $this->url, $this->type, $refType)
            ->originalValues();

        $this->reset();
        $this->fillProps($hook);

        return $this;
    }


    /**
     * Validate hook.
     *
     * @param string $code
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function validate(string $code) {
        if (!$this->hook_id)
            throw new \RuntimeException('Hook Id is required');

        return $this->request()->validate($this->hook_id, $code);
    }


    /**
     * Verify hook.
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function verify() {
        if (!$this->hook_id)
            throw new \RuntimeException('Hook Id is required');

        return $this->request()->verify($this->hook_id);
    }

    /**
     * Hook request.
     *
     * @return HookRequest
     */
    public function request(): HookRequest
    {
        return new HookRequest($this->podium);
    }
}