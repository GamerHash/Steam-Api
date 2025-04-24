<?php

use Orchestra\Testbench\TestCase;
use Syntax\SteamApi\Client;
use Dotenv\Dotenv;

class BaseTester extends TestCase {

    protected $id32      = 'STEAM_1:1:31085444';

    protected $id64      = 76561198022436617;

    protected $id3       = '[U:1:62170889]';

    protected $altId64   = 76561197979958413;

    protected $appId     = 620;

    protected $packageId = 32848;

    protected $itemAppId = 440;

    protected $groupId   = 103582791429521412;

    protected $groupName = 'Valve';

    protected $steamClient;

    protected function setUp(): void
    {
        parent::setUp();

        $root = dirname(__DIR__, 1);

        // Use .env when available
        if (file_exists($root . '/.env')) {
            $dotenv = Dotenv::createUnsafeImmutable($root);
            $dotenv->load();
        }

        $this->steamClient = new Client();
    }

    /** @test */
    public function empty_test()
    {
        $this->assertTrue(true);
    }

    protected function assertObjectHasAttributes($attributes, $object)
    {
        foreach ($attributes as $attribute) {
            $this->assertObjectHasProperty($attribute, $object);
        }
    }

    protected function checkSteamIdsProperties($steamId)
    {
        $attributes = [
            'id32', 'id64', 'id3', 'communityId', 'steamId'
        ];
        $this->assertObjectHasAttributes($attributes, $steamId);
    }

    protected function checkPlayerProperties($friendsList)
    {
        $attributes = [
            'steamId', 'steamIds', 'communityVisibilityState', 'profileState', 'lastLogoff', 'profileUrl', 'realName', 'primaryClanId', 'timecreated'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'avatar', 'avatarMedium', 'avatarFull', 'avatarUrl', 'avatarMediumUrl', 'avatarFullUrl',
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'personaName', 'personaState', 'personaStateId', 'personaStateFlags'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $attributes = [
            'locCountryCode', 'locStateCode', 'locCityId', 'location'
        ];
        $this->assertObjectHasAttributes($attributes, $friendsList[0]);

        $this->checkSteamIdsProperties($friendsList[0]->steamIds);
    }

    protected function checkAchievementProperties($achievement)
    {
        $attributes = [
            'apiName', 'achieved', 'name', 'description'
        ];
        $this->assertObjectHasAttributes($attributes, $achievement);
    }

    protected function checkAppProperties($app)
    {
        $this->checkMainAppProperties($app);
        $this->checkGeneralAppProperties($app);
        $this->checkNestedAppProperties($app);
    }

    protected function checkPackageProperties($package)
    {
        $this->checkNestedPackageProperties($package);
    }

    protected function checkGroupProperties($group)
    {
        $this->checkGroupMainSummaryProperties($group);
        $this->checkGroupDetailProperties($group);
        $this->checkGroupMemberDetailsProperties($group);
        $this->checkGroupMemberProperties($group);
    }

    /**
     * @param $item
     */
    protected function checkItemProperties($item)
    {
        $attributes = ['id', 'originalId', 'level', 'quality', 'quantity'];
        $this->assertObjectHasAttributes($attributes, $item);
    }

    /**
     * @param $app
     */
    private function checkMainAppProperties($app)
    {
        $attributes = [
            'id', 'type', 'name', 'controllerSupport', 'description', 'about', 'fullgame', 'header', 'website', 'shortDescription'
        ];
        $this->assertObjectHasAttributes($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkGeneralAppProperties($app)
    {
        $attributes = [
            'pcRequirements', 'legal', 'developers', 'publishers', 'price', 'platforms', 'metacritic', 'categories', 'genres', 'release', 'requiredAge', 'isFree', 'supportedLanguages', 'recommendations'
        ];
        $this->assertObjectHasAttributes($attributes, $app);
    }

    /**
     * @param $app
     */
    private function checkNestedAppProperties($app)
    {
        $this->assertObjectHasProperty('minimum', $app->pcRequirements);

        $attributes = ['currency', 'initial', 'final', 'discount_percent'];
        $this->assertObjectHasAttributes($attributes, $app->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasAttributes($attributes, $app->platforms);

        $attributes = ['score', 'url'];
        $this->assertObjectHasAttributes($attributes, $app->metacritic);

        $attributes = ['total'];
        $this->assertObjectHasAttributes($attributes, $app->recommendations);

        $attributes = ['total'];
        $this->assertObjectHasAttributes($attributes, $app->achievements);
    }

    /**
     * @param $package
     */
    private function checkNestedPackageProperties($package)
    {
        $attributes = ['currency', 'initial', 'final', 'discount_percent', 'individual'];
        $this->assertObjectHasAttributes($attributes, $package->price);

        $attributes = ['windows', 'mac', 'linux'];
        $this->assertObjectHasAttributes($attributes, $package->platforms);
    }

    /**
     * @param $group
     */
    private function checkGroupMainSummaryProperties($group)
    {
        $this->assertObjectHasProperty('groupID64', $group);
        $this->assertObjectHasProperty('groupDetails', $group);
        $this->assertObjectHasProperty('memberDetails', $group);
        $this->assertObjectHasProperty('startingMember', $group);
        $this->assertObjectHasProperty('members', $group);
    }

    /**
     * @param $group
     */
    private function checkGroupDetailProperties($group)
    {
        $this->assertObjectHasProperty('name', $group->groupDetails);
        $this->assertObjectHasProperty('url', $group->groupDetails);
        $this->assertObjectHasProperty('headline', $group->groupDetails);
        $this->assertObjectHasProperty('summary', $group->groupDetails);
        $this->assertObjectHasProperty('avatarIcon', $group->groupDetails);
        $this->assertObjectHasProperty('avatarMedium', $group->groupDetails);
        $this->assertObjectHasProperty('avatarFull', $group->groupDetails);
        $this->assertObjectHasProperty('avatarIconUrl', $group->groupDetails);
        $this->assertObjectHasProperty('avatarMediumUrl', $group->groupDetails);
        $this->assertObjectHasProperty('avatarFullUrl', $group->groupDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberDetailsProperties($group)
    {
        $this->assertObjectHasProperty('count', $group->memberDetails);
        $this->assertObjectHasProperty('inChat', $group->memberDetails);
        $this->assertObjectHasProperty('inGame', $group->memberDetails);
        $this->assertObjectHasProperty('online', $group->memberDetails);
    }

    /**
     * @param $group
     */
    private function checkGroupMemberProperties($group)
    {
        $startingMember = $group->members->get($group->startingMember);

        $this->assertObjectHasProperty('id32', $startingMember);
        $this->assertObjectHasProperty('id64', $startingMember);
        $this->assertObjectHasProperty('id3', $startingMember);
    }

}
