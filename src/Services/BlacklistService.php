<?php
/**
 * This source file is proprietary and part of Rebilly.
 *
 * (c) Rebilly SRL
 *     Rebilly Ltd.
 *     Rebilly Inc.
 *
 * @see https://www.rebilly.com
 */

namespace Rebilly\Services;

use ArrayObject;
use JsonSerializable;
use Rebilly\Entities\Blacklist;
use Rebilly\Http\Exception\NotFoundException;
use Rebilly\Http\Exception\UnprocessableEntityException;
use Rebilly\Paginator;
use Rebilly\Rest\Collection;
use Rebilly\Rest\Service;

/**
 * Class BlacklistService
 *
 */
final class BlacklistService extends Service
{
    /**
     * @param array|ArrayObject $params
     *
     * @return Blacklist[][]|Collection[]|Paginator
     */
    public function paginator($params = [])
    {
        return new Paginator($this->client(), 'blacklists', $params);
    }

    /**
     * @param array|ArrayObject $params
     *
     * @return Blacklist[]|Collection
     */
    public function search($params = [])
    {
        return $this->client()->get('blacklists', $params);
    }

    /**
     * @param string $blacklistId
     * @param array|ArrayObject $params
     *
     * @throws NotFoundException The resource data does not exist
     *
     * @return Blacklist
     */
    public function load($blacklistId, $params = [])
    {
        return $this->client()->get('blacklists/{blacklistId}', ['blacklistId' => $blacklistId] + (array) $params);
    }

    /**
     * @param array|JsonSerializable|Blacklist $data
     * @param string $blacklistId
     *
     * @throws UnprocessableEntityException The input data does not valid
     *
     * @return Blacklist
     */
    public function create($data, $blacklistId = null)
    {
        if (isset($blacklistId)) {
            return $this->client()->put($data, 'blacklists/{blacklistId}', ['blacklistId' => $blacklistId]);
        }

        return $this->client()->post($data, 'blacklists');
    }

    /**
     * @param string $blacklistId
     */
    public function delete($blacklistId)
    {
        $this->client()->delete('blacklists/{blacklistId}', ['blacklistId' => $blacklistId]);
    }
}
