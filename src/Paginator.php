<?php

namespace Endeavors\MaxMD\Message;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Paginator implements ArrayAccess, Countable, IteratorAggregate
{
    /**
	 * The items being paginated.
	 *
	 * @var array
     * @todo use ModernArray
	 */
    protected $items;

    /**
	 * The total number of items.
	 *
	 * @var int
	 */
    protected $total;

    /**
	 * The amount of items to show per page.
	 *
	 * @var int
	 */
    protected $perPage;
    
    /**
	 * Get the current page for the request.
	 *
	 * @var int
	 */
    protected $currentPage;
    
    /**
	 * Get the last available page number.
	 *
	 * @return int
	 */
    protected $lastPage;

    /**
	 * All of the additional query string values.
	 *
	 * @var array
	 */
	protected $query = array();

    public function __construct(array $items, $total, $perPage)
    {
        $this->items = $items;

        $this->total = (int)$total;

        $this->perPage = (int)$perPage;
    }

    /**
	 * Setup the pagination context (current and last page).
	 *
	 * @return \Endeavors\MaxMD\Message\Paginator
	 */
	public static function create(array $items, $total, $perPage)
	{
        $instance = new Paginator($items, $total, $perPage);

		$instance->calculate();

		return $instance;
    }
    
    public function calculate()
    {
        $this->calculateCurrentAndLastPages();
        
        $this->calculateItemRanges();
    }
    
    /**
     * Get the pages
     * 
     * @return array
     */
    public function pages($pageParameter = null)
    {
        $pages = [];

        for($i = 1; $i <= $this->getLastPage(); $i++ ) {
            if( null !== $pageParameter ) {
                // we'll define the parameter here so its plug and play
                $pages[] = [$pageParameter => $i];
            } else {
                // we assume here the developer will define their own page parameter e.g. ['mypage' => 1]
                $pages[] = $i;
            }
        }

        return $pages;
    }

    /**
	 * Add a query string value to the paginator.
	 *
	 * @param  string  $key
	 * @param  string  $value
	 * @return \Endeavors\MaxMD\Message\Paginator
	 */
	public function addQuery($key, $value)
	{
		$this->query[$key] = $value;

		return $this;
    }

    /**
	 * Add an array of query string values.
	 *
	 * @param  array  $keys
	 * @return \Endeavors\MaxMD\Message\Paginator
	 */
	protected function appendArray(array $keys)
	{
		foreach ($keys as $key => $value)
		{
			$this->addQuery($key, $value);
		}

		return $this;
	}

    /**
	 * Get the current page for the request.
	 *
	 * @param  int|null  $total
	 * @return int
	 */
	public function getCurrentPage($total = null)
	{
		if (is_null($total))
		{
			return $this->currentPage;
		}
		else
		{
			return min($this->currentPage, (int) ceil($total / $this->perPage));
		}
    }
    
    /**
	 * Do we have a next page?
	 *
	 * @return bool
	 */
    public function hasNext()
    {
        return $this->currentPage < $this->getLastPage(); 
    }

    /**
	 * Do we have a next page?
	 *
	 * @return bool
	 */
    public function hasPrevious()
    {
        return $this->currentPage > 1; 
    }
    
    /**
	 * Go to the next page if there is one
	 *
	 * @return \Endeavors\MaxMD\Message\Paginator
	 */
    public function next()
    {
        if( $this->hasNext() ) {
            $this->nextPage();
            $this->calculate();
        }

        return $this;
    }

    /**
	 * Go to the next page if there is one
	 *
	 * @return \Endeavors\MaxMD\Message\Paginator
	 */
    public function previous()
    {
        if( $this->hasPrevious() ) {
            $this->prevPage();
            $this->calculate();
        }

        return $this;
    }
    
    /**
     * The value of the next page
     * 
     * @return int
     */
    public function nextPage()
    {
        $this->currentPage++;

        return (int)$this->currentPage;
    }

    /**
     * The value of the previous page
     * 
     * @return int
     */
    public function prevPage()
    {
        $this->currentPage--;

        return (int)$this->currentPage;
    }
    
    /**
	 * Get the last page that should be available.
	 *
	 * @return int
	 */
	public function getLastPage()
	{
		return $this->lastPage;
    }
    
    /**
	 * Get the number of the first item on the paginator.
	 *
	 * @return int
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Get the number of the last item on the paginator.
	 *
	 * @return int
	 */
	public function getTo()
	{
		return $this->to;
    }

    /**
     * Get the slice according to the parameters
     * 
     * @return array
     */
    public function paginate()
    {
        return $this->sliced();
    }

    /**
     * Get the slice according to the parameters
     * 
     * @return array
     */
    public function sliced()
    {
        $offset = ($this->currentPage * $this->perPage) - $this->perPage;

        $sliced = array_slice($this->items,$offset,$this->perPage);
        
        return $sliced;
    }

    /**
	 * Get an iterator for the items.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->items);
	}

	/**
	 * Determine if the list of items is empty or not.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->items);
	}

    /**
	 * Get the number of items for the current page.
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

    /**
	 * Determine if the given item exists.
	 *
	 * @param  mixed  $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->items);
	}

	/**
	 * Get the item at the given offset.
	 *
	 * @param  mixed  $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->items[$key];
	}

	/**
	 * Set the item at the given offset.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$this->items[$key] = $value;
	}

	/**
	 * Unset the item at the given key.
	 *
	 * @param  mixed  $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->items[$key]);
	}
    
    /**
	 * Calculate the current and last pages for this instance.
	 *
	 * @return void
	 */
	protected function calculateCurrentAndLastPages()
	{
		$this->lastPage = (int) ceil($this->total / $this->perPage);

		$this->currentPage = $this->calculateCurrentPage($this->lastPage);
	}

	/**
	 * Calculate the first and last item number for this instance.
	 *
	 * @return void
	 */
	protected function calculateItemRanges()
	{
        $this->from = $this->total ? ($this->currentPage - 1) * $this->perPage + 1 : 0;

		$this->to = min($this->total, $this->currentPage * $this->perPage);
    }
    
    /**
	 * Get the current page for the request.
	 *
	 * @param  int  $lastPage
	 * @return int
	 */
	protected function calculateCurrentPage($lastPage)
	{
		$page = (int) $this->currentPage ?: 1;

		// The page number will get validated and adjusted if it either less than one
		// or greater than the last page available based on the count of the given
		// items array. If it's greater than the last, we'll give back the last.
		if (is_numeric($page) && $page > $lastPage)
		{
			return $lastPage > 0 ? $lastPage : 1;
		}

		return $this->isValidPageNumber($page) ? (int) $page : 1;
    }
    
    /**
	 * Determine if the given value is a valid page number.
	 *
	 * @param  int  $page
	 * @return bool
	 */
	protected function isValidPageNumber($page)
	{
		return $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false;
	}
}