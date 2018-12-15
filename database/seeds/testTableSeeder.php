<?php

use Illuminate\Database\Seeder;

class testTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tags = [] ;
        for( $i = 0 ; $i < 100 ; $i++ ) {
            $tags[] = str_random( rand( 5 , 10 ) ) ;
        }
        for( $i = 0 ; $i < 1500000 ; $i++ ) {


            $insert = [
                'name' => str_random(rand(20, 100)),
                'cate_id' => rand(1, 20),
                'tag_id' => rand(1, 100),
                'price' => rand(100, 10000) / 100,
                'tags' => $tags[ array_rand( $tags )],
                'created_at' => date('Y-m-d H:i:s'),
                'create_time' => time()
            ];
            \DB::table('test_table')->insert($insert);
        }

    }
}
