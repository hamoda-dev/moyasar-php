<?php

namespace Tests\Config;

use HamodaDev\Moyasar\Moyasar;
use Saloon\Http\Faking\MockClient;

class MoyasarInitializer
{
    private static ?self $instance = null;

    private function __construct(
        private readonly Moyasar $moyasar,
        private ?ExtraData $extraData = null
    ) {
    }

    public static function getInstance(): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $isMock = empty(getenv('REAL_TESTS')) || getenv('REAL_TESTS') === 'false';

        if ($isMock) {
            $moyasar = new Moyasar(
                baseUrl: getenv('MOYASAR_BASE_URL') ?: "https://mock-api.moyasar.com/v1",
                apiKey: "sk_test_AbCd",
            );

            /** @var array<class-string, \Saloon\Http\Faking\MockResponse> */
            $mockRouter = require_once "MockResponses.php";

            $extra = new ExtraData(new MockClient($mockRouter));
            $moyasar->withMockClient($extra->getClient());
        } else {
            $moyasar = new Moyasar(
                baseUrl: getenv('MOYASAR_BASE_URL'),
                apiKey: getenv('MOYASAR_SECRET_KEY'),
            );
            $extra = null;
        }

        self::$instance = new self($moyasar, $extra);
        return self::$instance;
    }

    public function getMoyasar(): Moyasar
    {
        return $this->moyasar;
    }

    public function getExtraData(): ?ExtraData
    {
        return $this->extraData;
    }
}
