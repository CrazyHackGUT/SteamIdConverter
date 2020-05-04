<?php


namespace Kruzya\SteamIdConverter;


use Kruzya\SteamIdConverter\Exception\InvalidSteamIdException;

class SteamID
{
    /**
     * SteamID CommunityID Base integer.
     * This is constantly for all users.
     *
     * @var string
     */
    const STEAM_COMMUNITY_ID_BASE = '76561197960265728';

    /** @var integer */
    protected $userIdentifier;

    /**
     * SteamID constructor.
     * @param $steamId
     * @throws InvalidSteamIdException
     */
    public function __construct($steamId)
    {
        $this->userIdentifier = $this->resolveSteamId($steamId);
    }

    /**
     * Returns the Community ID (SteamID 64).
     *
     * @return string
     */
    public function communityId()
    {
        return gmp_strval(gmp_add(self::STEAM_COMMUNITY_ID_BASE, $this->userIdentifier));
    }

    /**
     * Returns the SteamID v2.
     *
     * @param int $x
     * @return string
     */
    public function v2($x = 0)
    {
        return sprintf('STEAM_%d:%s', $x, $this->v2WithoutX());
    }

    /**
     * Returns the SteamID v2 without part "STEAM_X".
     *
     * @return string
     */
    public function v2WithoutX()
    {
        $y = $this->userIdentifier % 2;
        $z = ($this->userIdentifier - $y) / 2;

        return sprintf('%d:%d', $y, $z);
    }

    /**
     * Returns the SteamID v3.
     *
     * @return string
     */
    public function v3()
    {
        return sprintf('[U:1:%d]', $this->userIdentifier);
    }

    /**
     * Returns the Steam Account ID.
     *
     * @return int
     */
    public function accountId()
    {
        return $this->userIdentifier;
    }

    /**
     * Resolves SteamID to AccountID (unified identifier).
     *
     * @param $steamId
     * @throws InvalidSteamIdException
     */
    protected function resolveSteamId($steamId)
    {
        // Check is this SteamID v2.
        if (strncmp('STEAM_', $steamId, 6) == 0)
        {
            $parts = explode(':', $steamId);
            if (count($parts) != 3)
            {
                throw new InvalidSteamIdException("Invalid SteamIDv2");
            }

            return intval($parts[2] * 2) + intval($parts[1]);
        }

        // Check is this SteamID v3.
        if (strncmp('[U:1:', $steamId, 4) == 0)
        {
            $parts = explode(':', $steamId);
            if (count($parts) != 3)
            {
                throw new InvalidSteamIdException("Invalid SteamIDv3");
            }

            return intval(substr($parts[2], 0, -1));
        }

        // Check is this SteamID Community ID.
        if (strncmp('7656119', $steamId, 7) == 0 && strlen($steamId) == 17)
        {
            return gmp_intval(gmp_sub($steamId, self::STEAM_COMMUNITY_ID_BASE));
        }

        // And last try determine what is this.
        // If this fully integer - maybe this is Account ID already?
        if (preg_match('/^\d{1,}$/', $steamId))
        {
            return intval($steamId);
        }

        // This is unknown SteamID type. Just throw exception.
        throw new InvalidSteamIdException("Unknown SteamID type");
    }
}