<?php

namespace Syntax\SteamApi\Steam;

use Syntax\SteamApi\Client;
use Illuminate\Support\Collection;
use Syntax\SteamApi\Containers\Item as ItemContainer;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Inventory;

class Item extends Client
{
    public function __construct()
    {
        parent::__construct();
        $this->url       = 'http://store.steampowered.com/';
        $this->isService = true;
        $this->interface = 'api';
    }

    public function GetPlayerItems($appId, $steamId)
    {
        // Set up the api details
        $this->url       = 'http://api.steampowered.com/';
        $this->interface = 'IEconItems_' . $appId;
        $this->method    = __FUNCTION__;
        $this->version   = 'v0001';

        $arguments = ['steamId' => $steamId];

        $client = $this->setUpClient($arguments);

        // Clean up the items
        $items = $this->convertToObjects($client->result->items);

        // Return a full inventory
        return new Inventory($client->result->num_backpack_slots, $items);
    }

    public function getPlayerItemsV2($appId, $steamId)
    {
        $this->interface = null;
        $this->method = 'inventory';

        $this->url = 'https://steamcommunity.com/inventory/'.$steamId.'/'.$appId.'/2';
        // Set up the arguments
        $arguments = [
            'l' => 'english',
            'count'=> 5000
        ];
        try {
            $client = $this->setUpClient($arguments);
            return $client->descriptions;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function convertToObjects($items)
    {
        return $this->convertItems($items);
    }

    /**
     * @param array $items
     *
     * @return Collection
     */
    protected function convertItems($items)
    {
        $convertedItems = new Collection();

        foreach ($items as $item) {
            $convertedItems->add(new ItemContainer($item));
        }

        return $convertedItems;
    }
}
