<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\CheckersClass\gameStart;
use App\CheckersClass\gameEnd;
use App\User;
use phpDocumentor\Reflection\Types\This;

class GameEndTest extends TestCase
{
    /**
     * @dataProvider endProvider
     */
    public function testEnd($item, $result, $orderid, $shouldBe)
    {
        $subject = new gameEnd();
        $this->loginWithFakeUser();

        $re = $subject->end($item, $result, $orderid);
        $this->assertEquals($shouldBe, $re);
    }
    public function endProvider()
    {
        $result =array(array(1,1,3),array(2,3,2),array(3,2,1));

        $item = array('特殊-123','6','5.0000','12','1' );
        $shouldBe = $item;
        array_push($shouldBe, true);

        return[[$item,$result,1, $shouldBe]];
    }
    public function loginWithFakeUser()
    {
        $user = new User([
        'id' => 1,
        'name' => 'ttt'
    ]);

        $this->be($user);
    }
}
