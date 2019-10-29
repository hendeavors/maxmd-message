<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\Paginator;

class PaginatorTest extends TestCase
{
    protected $items;

    public function setUp()
    {
        for($i = 0;$i < 100;$i++) {
            $this->items[] = chr($i);
        }

        parent::setUp();
    }

    public function testPagination()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
    }

    public function testPages()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);

        $pages = [
            1,
            2,
            3,
            4
        ];

        $this->assertEquals($pages, $paginator->pages());
    }

    public function testPagesWithParameter()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);

        $pages = [
            ['page' => 1],
            ['page' => 2],
            ['page' => 3],
            ['page' => 4],
        ];

        $this->assertEquals($pages, $paginator->pages('page'));
    }

    public function testLastPageIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // last page should be 4
        $this->assertEquals(4, $paginator->getLastPage());
    }

    public function testCurrentPageIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // current page should be 1
        $this->assertEquals(1, $paginator->getCurrentPage());

        $newPaginator = $paginator->next();
        // current page should be 2
        $this->assertEquals(2, $paginator->getCurrentPage());
        // test reassignment. should be the same
        $this->assertEquals(2, $newPaginator->getCurrentPage());
    }

    public function testHasNextPageIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // we should have a page 2
        $this->assertTrue($paginator->hasNext());
        // go to page 2
        $paginator->next();
        // we should have a page 3
        $this->assertTrue($paginator->hasNext());
        // go to page 3
        $paginator->next();
        // we should have a page 4
        $this->assertTrue($paginator->hasNext());
        // go to page 4
        $paginator->next();
        // we should not have a page 5
        $this->assertFalse($paginator->hasNext());
    }

    public function testHasPreviousPageIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // we should not have a page 0
        $this->assertFalse($paginator->hasPrevious());
        // go to page 2
        $paginator->next();
        // we should have a page 1
        $this->assertTrue($paginator->hasPrevious());
        // go to page 3
        $paginator->next();
        // we should have a page 2
        $this->assertTrue($paginator->hasPrevious());
        // go to page 4
        $paginator->next();
        // we should have a page 3
        $this->assertTrue($paginator->hasPrevious());
    }

    public function testPaginationCount()
    {

    }

    public function testFromIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // on page 1 from should be 1
        $this->assertEquals(1, $paginator->getFrom());

        $paginator = $paginator->next();
        // on page 2 from should be 26
        $this->assertEquals(26, $paginator->getFrom());

        $paginator->next();
        // on page 3 from should be 51
        $this->assertEquals(51, $paginator->getFrom());

        $paginator->next();
        // on page 4 from should be 76
        $this->assertEquals(76, $paginator->getFrom());
        // if the next api is used we'll default to the last page
        $paginator->next();
        // on page 4 from should be 76
        $this->assertEquals(76, $paginator->getFrom());
        // go back to page 3
        $paginator->previous();
        // on page 3 from should be 51
        $this->assertEquals(51, $paginator->getFrom());
    }

    public function testToIsAccurate()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);
        // on page 1 to should be 25
        $this->assertEquals(25, $paginator->getTo());

        $paginator = $paginator->next();
        // on page 2 to should be 50
        $this->assertEquals(50, $paginator->getTo());

        $paginator->next();
        // on page 3 to should be 75
        $this->assertEquals(75, $paginator->getTo());

        $paginator->next();
        // on page 4 to should be 100
        $this->assertEquals(100, $paginator->getTo());
        // if the next api is used we'll default to the last page
        $paginator->next();
        // on page 4 to should be 100
        $this->assertEquals(100, $paginator->getTo());
        // go back to page 3
        $paginator->previous();
        // on page 3 to should be 75
        $this->assertEquals(75, $paginator->getTo());
    }

    public function testPaginationSlice()
    {
        $paginator = Paginator::create($this->items, count($this->items), 25);

        $firstPageItems = $paginator->paginate();

        $this->assertEquals(count($firstPageItems), 25);

        $paginator->next();

        $secondPageItems = $paginator->paginate();

        $this->assertEquals(count($secondPageItems), 25);

        $this->assertNotEquals($firstPageItems, $secondPageItems);
    }
}
