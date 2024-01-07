<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenges = array();
        $challenges[] = [
            'name' => json_encode(['en' => '100 meter sprint', 'ar' => 'الركض لمئة متر']),
            'description' => json_encode(['en' => 'you need to take a video for your self sprinting for one hundred meter', 'ar' => 'عليك تصوير مقطع فيديو لنفسك و انت تقطع مسافة مئة متر']),

        ];
        $challenges[] = [
            'name' => json_encode(['en' => 'bronze foot', 'ar' => 'القدم البرونزية']),
            'description' => json_encode(['en' => 'show your shoot accuracy by scoring one ball from a standing point (penalty point)', 'ar' => 'أظهر دقة تسديدك من خلال تسجيل هدف واحد من نقطة الوقوف (نقطة الجزاء)']),

            'sport_id' => 1,
            'positions' => json_encode([16, 15, 14, 13, 12, 11]),
        ];
        $challenges[] = [
            'name' => json_encode(['en' => 'silver foot', 'ar' => 'القدم الفضية']),
            'description' => json_encode(['en' => 'show your shoot accuracy by scoring 3 balls from a standing point (penalty point)', 'ar' => 'أظهر دقة تسديدك من خلال تسجيل ثلاث كرات من نقطة الوقوف (نقطة الجزاء)']),

            'sport_id' => 1,
            'positions' => json_encode([16, 15, 14, 13, 12, 11]),
        ];
        $challenges[] = [
            'name' => json_encode(['en' => 'gold foot', 'ar' => 'القدم الذهبية']),
            'description' => json_encode(['en' => 'show your shoot accuracy by scoring 5 balls from a standing point (penalty point)', 'ar' => 'أظهر دقة تسديدك من خلال تسجيل خمس كرات من نقطة الوقوف (نقطة الجزاء)']),

            'sport_id' => 1,
            'positions' => json_encode([16, 15, 14, 13, 12, 11]),
        ];
        $challenges[] = [
            'name' => json_encode(['en' => 'bronze ', 'ar' => 'الدرع البرونزي']),
            'description' => json_encode(['en' => 'show your shoot accuracy by scoring 5 balls from a standing point (penalty point)', 'ar' => 'أظهر دقة تسديدك من خلال تسجيل خمس كرات من نقطة الوقوف (نقطة الجزاء)']),
            'sport_id' => 1,
            'positions' => json_encode([16, 15, 14, 13, 12, 11]),
        ];
        foreach ($challenges as $challenge) {
            Challenge::query()->insert($challenge);
        }
    }
}
