<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class positionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = array();
        $positions[] = ['name' => json_encode(['en' => 'Goalkeeper', 'ar' => 'حارس المرمى']), 'code' => 'GK', 'parent_id' => 0, 'sport_id' => 1];
        $positions[] = ['name' => json_encode(['en' => 'Defender', 'ar' => 'مدافع']), 'code' => 'D', 'parent_id' => 0, 'sport_id' => 1];
        $positions[] = ['name' => json_encode(['en' => 'Mid Fielder', 'ar' => 'خط الوسط']), 'code' => 'MD', 'parent_id' => 0, 'sport_id' => 1];
        $positions[] = ['name' => json_encode(['en' => 'Attacker', 'ar' => 'مهاجم']), 'code' => 'A', 'parent_id' => 0, 'sport_id' => 1];


        $positions[] = ['name' => json_encode(['en' => 'Sweeper', 'ar' => 'مدافع متغير']), 'code' => 'SW', 'parent_id' => 2, 'sport_id' => 1];//parent defender
        $positions[] = ['name' => json_encode(['en' => 'Left Back', 'ar' => 'مدافع أيسر']), 'code' => 'LB', 'parent_id' => 2, 'sport_id' => 1];//parent defender
        $positions[] = ['name' => json_encode(['en' => 'Right Back', 'ar' => 'مدافع أيمن']), 'code' => 'RB', 'parent_id' => 2, 'sport_id' => 1];//parent defender
        $positions[] = ['name' => json_encode(['en' => 'Central Back', 'ar' => 'مدافع مركزي']), 'code' => 'CB', 'parent_id' => 2, 'sport_id' => 1];//parent defender


        $positions[] = ['name' => json_encode(['en' => 'Left Wing Back', 'ar' => 'لاعب وسط أيسر متأخر']), 'code' => 'LWB', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Right Wing Back', 'ar' => 'لاعب وسط أيمن متأخر']), 'code' => 'RWB', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Defending Mid Fielder', 'ar' => 'لاعب وسط دفاعي']), 'code' => 'DM', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Left Mid Fielder', 'ar' => 'لاعب وسط أيسر']), 'code' => 'LM', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Right Mid Fielder', 'ar' => 'لاعب وسط أيمن']), 'code' => 'RM', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Central Mid Fielder', 'ar' => 'لاعب وسط أرتكاز']), 'code' => 'CM', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder
        $positions[] = ['name' => json_encode(['en' => 'Attacking Mid Fielder', 'ar' => 'لاعب وسط مهاجم(صانع ألعاب)']), 'code' => 'AM', 'parent_id' => 3, 'sport_id' => 1];//parent midfilder


        $positions[] = ['name' => json_encode(['en' => 'Left Winger', 'ar' => 'جناح أيسر']), 'code' => 'LW', 'parent_id' => 4, 'sport_id' => 1];//parent atacker
        $positions[] = ['name' => json_encode(['en' => 'Right Winger', 'ar' => 'جناح أيمن']), 'code' => 'RW', 'parent_id' => 4, 'sport_id' => 1];//parent atacker
        $positions[] = ['name' => json_encode(['en' => 'Seconder Striker', 'ar' => 'مهاجم وهمي']), 'code' => 'SS', 'parent_id' => 4, 'sport_id' => 1];//parent atacker
        $positions[] = ['name' => json_encode(['en' => 'Central Forward', 'ar' => 'رأس حربة']), 'code' => 'CF', 'parent_id' => 4, 'sport_id' => 1];//parent atacker


        Position::query()->insert($positions);
    }
}
