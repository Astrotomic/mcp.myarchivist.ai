<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\AskCampaignToolTest as RealWorldAskCampaignToolTest;

final class AskCampaignToolTest extends RealWorldAskCampaignToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            [['question' => 'What is Hammerhaeim?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'What is Triboar?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'What is the name of the now destroyed estate?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'Which airships exist?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'Which is the latest session?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'What happened during the latest 20 beats?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'Who are the zhentarims?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'Welche Haustiere hat Surina?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'What happened during the latest 5 moments?'], 'cmj78gm6k000004jrvzm7gcjr'],
            [['question' => 'Which quests are ongoing atm?'], 'cmj78gm6k000004jrvzm7gcjr'],
        ];
    }
}
