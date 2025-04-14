<?php

namespace Tests\Unit;

use App\Bar;
use App\Baz;
use App\Foo;
use App\Models\Message;
use PHPUnit\Framework\TestCase;
use Mockery;

class MessageTest extends TestCase
{
    /**
     * Eloquentのモックへのプロパティへの代入
     */
    public function test_Eloquent_property_access(): void
    {
        $messageEloquentMock = Mockery::mock(Message::class);

        /**
         * これはエラーになる
         * エラー内容：Mockery\Exception\BadMethodCallException: Received Mockery_0_App_Models_Message::setAttribute(), but no expectations were specified
         */
        // $messageEloquentMock->content = 'そんざいしないプロパティです';



        $bar = Mockery::mock(Bar::class);

        /**
         * これもエラーになる、__setのマジックメソッドでsetPropのメソッドを読んでいるので
         * Mockery\Exception\BadMethodCallException: Received Mockery_2_App_Bar::setProp(), but no expectations were specified
         * なので、Mockeryは通常のメソッドと違い__setのマジックメソッドは、モック元のクラスの__setを呼んでしまう。
         */
        //$bar->hoge = 'そんざいしないプロパティです';

        // これはエラーにならない、__setのマジックメソッドを利用していないので
        $foo = Mockery::mock(Foo::class);
        $foo->hoge = 'そんざいしないプロパティです';
        $this->assertSame('そんざいしないプロパティです', $foo->hoge);

        /**
        なので、Eloquentのモックにプロパティを代入する時は、__setのマジックメソッドが利用している、setAttributeをモックする必要がある
        public function __set($key, $value)
        {
            $this->setAttribute($key, $value);
        }
        */

        $messageEloquentMock->shouldReceive('setAttribute')
            ->with('content', 'そんざいしないプロパティです');

        $messageEloquentMock->content = 'そんざいしないプロパティです';


        /**
         また __getのマジックメソッドは、モック元のクラスの__getを呼んでしまう。
         なので 、Eloquentのモックにプロパティを取得する時は、__getのマジックメソッドが利用している、getAttributeをモックする必要がある
        public function __get($key)
        {
            return $this->getAttribute($key);
        }
        */
        $messageEloquentMock->shouldReceive('getAttribute')->with('content')->andReturn('hogehoge');
        $this->assertSame('hogehoge', $messageEloquentMock->content);

        /**
         * またこれでも、エラーにならない
         * makePartialは部分モックで、定義したモックした内容以外は、モック元のメソッドを呼びだすので
         */
        $messageEloquentMock->makePartial();

        $messageEloquentMock->content = 'そんざいしないプロパティです';

        // EloquentはArrayAccessを実装しているので、issetでプロパティを判定すると
        // Received Mockery_0_App_Models_Message::offsetExists(), but no expectations were specifiedのエラーになる
        // isset($messageEloquentMock->content);
        // なので、offsetExistsをモックする必要がある
        $messageEloquentMock->shouldReceive('offsetExists')
            ->with('content')
            ->andReturn(true);
        $this->assertTrue(isset($messageEloquentMock->content));


        $this->assertTrue(true);
    }


    public function test_ETC()
    {
        $foo = Mockery::mock(Foo::class);

        // shouldReceive してないのでエラーになる
        // $foo->bar();

        // モック元の public int $foo を判断している
        $foo->foo = 123;

        // モック元の private $privateFoo を判断していない
        $foo->privateFoo = 'プライベードへのアクセス';



        $baz = Mockery::mock(Baz::class);

        // コンストラクタに引数を渡す方法
        $baz->makePartial();
        $baz->__construct(123456);

        $this->assertSame(123456, $baz->poyo);
    }
}
