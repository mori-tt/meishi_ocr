<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('business_cards')->delete();
        DB::table('business_cards')->insert([
            'id' => IdGenerator::increment(),
            'name' => '田中太郎',
            'company_name' => 'テスト株式会社',
            'post_code' => '111-1111',
            'address' => '東京都千代田区〇〇1-1-1',
            'phone' => '111-1111-1111',
            'fax' => '111-1111-1111',
            'email' => 'tanaka-taro@test.com',
            'image' => '123456789.jpeg',
            'created_at' => Date::create(2024, 01, 01, 0, 0, 0),
            'updated_at' => Date::create(2024, 01, 01, 0, 0, 0),
        ]);
    }
}

class IdGenerator
{
    private static $id = 1;

    public static function increment(): int
    {
        return self::$id++;
    }
}
